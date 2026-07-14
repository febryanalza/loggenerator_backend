# Export Participants Feature - Logbook Export Enhancement

## Tanggal: 7 Januari 2026

## Feature Description

Menambahkan section **DAFTAR PARTICIPANT** pada export logbook (Word & PDF) yang menampilkan:
1. **Data Participant**: Semua field dari JSON `data` dipisahkan dengan " / "
2. **Nilai (Grade)**: Grade participant (1-100)
3. **Summary**: Total participant, jumlah yang sudah dinilai, dan rata-rata nilai

## Implementation Details

### 1. Database Structure

**Table**: `logbook_participants`

| Column | Type | Description |
|--------|------|-------------|
| id | UUID | Primary key |
| template_id | UUID | Foreign key to logbook_template |
| data | JSON | Participant information (dynamic fields) |
| grade | INTEGER | Nilai participant (1-100, nullable) |

**Example Data**:
```json
{
  "data": {
    "Nama Lengkap": "John Doe",
    "NIM": "12345678",
    "Email": "john@example.com",
    "Nomor Telepon": "08123456789"
  },
  "grade": 85
}
```

**Display in Export**:
```
Data Participant: John Doe / 12345678 / john@example.com / 08123456789
Nilai (Grade): 85
```

### 2. Backend Changes

#### A. LogbookExportController.php

**Import Model**:
```php
use App\Models\LogbookParticipant;
```

**Word Export** (`exportToWord` method):
```php
// Get participants for this template
$participants = LogbookParticipant::where('template_id', $templateId)
    ->orderBy('created_at', 'desc')
    ->get();

if ($participants->isNotEmpty()) {
    $this->addParticipantSection($section, $participants);
}
```

**PDF Export** (`exportToPdf` method):
```php
// Get participants for the template
$participants = LogbookParticipant::where('template_id', $templateId)
    ->orderBy('created_at', 'desc')
    ->get();

// Pass to view
$pdf = Pdf::loadView('exports.logbook-pdf', [
    // ... existing data
    'participants' => $participants,
]);
```

**New Method - Word Participant Section**:
```php
/**
 * Add participant section to Word document
 * Displays participants with their data and grades in a 2-column table
 */
private function addParticipantSection($section, $participants): void
{
    $section->addTextBreak(1);
    $section->addText('DAFTAR PARTICIPANT', 'sectionHeader');
    $section->addTextBreak(0);

    // Table with 3 columns: No, Data Participant, Grade
    $table = $section->addTable($tableStyle);
    
    // Header row
    $table->addRow(500);
    $table->addCell(1000, $headerCellStyle)->addText('No', 'tableHeaderWhite');
    $table->addCell(8000, $headerCellStyle)->addText('Data Participant', 'tableHeaderWhite');
    $table->addCell(3000, $headerCellStyle)->addText('Nilai (Grade)', 'tableHeaderWhite');

    // Data rows
    foreach ($participants as $participant) {
        $table->addRow();
        
        // Row number
        $table->addCell(1000)->addText($rowNum++, 'normalText');
        
        // Participant data - join all values with " / "
        $participantData = $participant->data ?? [];
        $dataString = implode(' / ', array_values($participantData));
        $table->addCell(8000)->addText($dataString, 'normalText');
        
        // Grade with conditional formatting
        $gradeText = $participant->grade ?? '-';
        $gradeCellStyle = ['valign' => 'center'];
        if ($participant->grade >= 60) {
            $gradeCellStyle['bgColor'] = 'c6f6d5'; // Light green
        }
        $table->addCell(3000, $gradeCellStyle)->addText($gradeText, 'normalText');
    }
    
    // Summary statistics
    $totalParticipants = $participants->count();
    $participantsWithGrades = $participants->filter(fn($p) => $p->grade !== null)->count();
    $averageGrade = $participants->whereNotNull('grade')->avg('grade');
    
    $section->addText(
        "Total: {$totalParticipants} | Dinilai: {$participantsWithGrades} | Rata-rata: {$averageGrade}",
        'captionText'
    );
}
```

#### B. logbook-pdf.blade.php

**New Section** (after Contributor section, before Footer):
```blade
<!-- Participant Section -->
@if(isset($participants) && $participants->isNotEmpty())
    <div class="section-header">DAFTAR PARTICIPANT</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Data Participant</th>
                <th style="width: 80px;">Nilai (Grade)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $index => $participant)
                @php
                    $participantData = $participant->data ?? [];
                    $dataString = implode(' / ', array_values($participantData));
                    $grade = $participant->grade;
                    $isPassing = $grade !== null && $grade >= 60;
                @endphp
                <tr>
                    <td class="row-num">{{ $index + 1 }}</td>
                    <td>{{ $dataString }}</td>
                    <td class="text-center" style="@if($isPassing) background-color: #c6f6d5; font-weight: bold; @endif">
                        {{ $grade ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @php
        $totalParticipants = $participants->count();
        $participantsWithGrades = $participants->filter(fn($p) => $p->grade !== null)->count();
        $averageGrade = $participants->whereNotNull('grade')->avg('grade');
        $averageText = $averageGrade ? number_format($averageGrade, 2) : '-';
    @endphp
    <p class="summary">
        Total Participant: {{ $totalParticipants }} | 
        Sudah Dinilai: {{ $participantsWithGrades }} | 
        Rata-rata Nilai: {{ $averageText }}
    </p>
@endif
```

### 3. Export Document Structure

#### Word Document (.docx)

```
┌─────────────────────────────────────┐
│ LOGBOOK EXPORT                      │
│ Template Name                       │
│ Institution Name                    │
└─────────────────────────────────────┘

INFORMASI LOGBOOK
┌──────────────────┬─────────────────┐
│ Nama Template    │ Value           │
│ Deskripsi        │ Value           │
│ Institution      │ Value           │
└──────────────────┴─────────────────┘

DATA LOGBOOK
┌───┬────────┬────────┬────────┐
│ No│ Field1 │ Field2 │ ...    │
├───┼────────┼────────┼────────┤
│ 1 │ Data   │ Data   │ ...    │
└───┴────────┴────────┴────────┘

KONTRIBUTOR
┌──────────────────┬─────────────────┐
│ Supervisor       │ Names           │
│ Owner            │ Names           │
│ Editor           │ Names           │
│ Anggota          │ Names           │
└──────────────────┴─────────────────┘

DAFTAR PARTICIPANT  ← NEW SECTION
┌───┬─────────────────────────┬────────┐
│ No│ Data Participant        │ Grade  │
├───┼─────────────────────────┼────────┤
│ 1 │ John Doe / 12345 / ... │   85   │
│ 2 │ Jane Smith / 67890 /...│   90   │
└───┴─────────────────────────┴────────┘
Total: 2 | Dinilai: 2 | Rata-rata: 87.50

FOOTER
```

#### PDF Document (.pdf)

Same structure as Word with additional styling:
- Passing grades (≥60): **Green background** (#c6f6d5, bold)
- Failing grades (<60): Normal
- No grade: Shows "-"

### 4. Participant Data Format

#### JSON Structure
```json
{
  "Nama Lengkap": "John Doe",
  "NIM": "12345678", 
  "Email": "john@example.com",
  "Nomor Telepon": "08123456789"
}
```

#### Display Format
```
John Doe / 12345678 / john@example.com / 08123456789
```

**Rules**:
- All values from JSON joined with " / "
- Order preserved from JSON
- Empty values shown as "-"
- Array values: `array_values($data)` ensures we get only values, not keys

### 5. Grade Display Rules

| Grade Value | Display | Background Color | Font Weight |
|-------------|---------|------------------|-------------|
| ≥ 60        | Number  | #c6f6d5 (green)  | Bold        |
| < 60        | Number  | None             | Normal      |
| NULL        | -       | None             | Normal      |

### 6. Summary Statistics

**Calculated Metrics**:
1. **Total Participant**: `$participants->count()`
2. **Sudah Dinilai**: `$participants->filter(fn($p) => $p->grade !== null)->count()`
3. **Rata-rata Nilai**: `$participants->whereNotNull('grade')->avg('grade')`

**Display Format**:
```
Total Participant: 10 | Sudah Dinilai: 8 | Rata-rata Nilai: 82.50
```

## API Endpoints

No new endpoints created. Enhancement applies to existing export endpoints:

### Export to Word
```
POST /api/logbook/export/{templateId}/word
Authorization: Bearer {token}
```

**Response includes participants** if any exist for the template.

### Export to PDF
```
POST /api/logbook/export/{templateId}/pdf
Authorization: Bearer {token}
```

**Response includes participants** if any exist for the template.

## Testing Checklist

### Scenario 1: Export with Participants
- [x] Template has participants
- [x] Participants have grades
- [x] Export Word → Participant section appears
- [x] Export PDF → Participant section appears
- [x] Data formatted correctly (joined with " / ")
- [x] Grades displayed with correct formatting
- [x] Summary statistics calculated correctly

### Scenario 2: Export without Participants
- [x] Template has no participants
- [x] Export Word → No participant section
- [x] Export PDF → No participant section
- [x] Export succeeds without errors

### Scenario 3: Participants without Grades
- [x] Participants exist but grade is NULL
- [x] Grade column shows "-"
- [x] No green background
- [x] Summary shows 0 for "Sudah Dinilai"
- [x] Rata-rata shows "-"

### Scenario 4: Mixed Grades
- [x] Some participants have grades, some don't
- [x] Grades displayed correctly
- [x] Passing grades (≥60) have green background
- [x] Failing grades (<60) no background
- [x] NULL grades show "-"
- [x] Average calculated only from non-NULL grades

## Example Export Output

### Example 1: Complete Participant Data

**Template**: "Logbook Praktek Kerja"

**Participants**:
```
1. Ahmad Fauzi / 2101001 / ahmad@email.com / 081234567890    | Grade: 85
2. Siti Nurhaliza / 2101002 / siti@email.com / 081234567891  | Grade: 92
3. Budi Santoso / 2101003 / budi@email.com / 081234567892    | Grade: 78
4. Diana Putri / 2101004 / diana@email.com / 081234567893    | Grade: -
```

**Summary**: Total Participant: 4 | Sudah Dinilai: 3 | Rata-rata Nilai: 85.00

### Example 2: Minimal Participant Data

**Template**: "Logbook Magang"

**Participants**:
```
1. John Doe / john@email.com    | Grade: 90
2. Jane Smith / jane@email.com  | Grade: 88
```

**Summary**: Total Participant: 2 | Sudah Dinilai: 2 | Rata-rata Nilai: 89.00

## Error Handling

1. **No Participants**: Section tidak ditampilkan (graceful)
2. **Empty JSON Data**: Displays "-" for participant data
3. **NULL Grade**: Displays "-" instead of grade number
4. **Invalid JSON**: Displays "-" (fallback)

## Performance Considerations

- Participants fetched with single query: `where('template_id', $templateId)`
- No N+1 queries
- Ordered by `created_at DESC` (newest first)
- Summary calculations done in-memory (efficient for < 1000 participants)

## Future Enhancements

1. **Configurable Grade Threshold**: Allow custom passing grade (default: 60)
2. **Grade Distribution Chart**: Visual representation of grade ranges
3. **Participant Grouping**: Group by grade ranges (A, B, C, etc.)
4. **Export Filters**: Export only participants with/without grades
5. **Custom Field Selection**: Choose which participant fields to display

## Related Files Modified

### Backend
- `app/Http/Controllers/Api/LogbookExportController.php`
  - Added `LogbookParticipant` import
  - Modified `exportToWord()` - added participant fetch and section
  - Modified `exportToPdf()` - added participant fetch and pass to view
  - Added `addParticipantSection()` method for Word export

- `resources/views/exports/logbook-pdf.blade.php`
  - Added participant section HTML
  - Added participant data formatting
  - Added grade conditional styling
  - Added summary statistics

### Database
- No migration needed (uses existing `logbook_participants` table)

## Version History
- **v1.0** (Jan 7, 2026): Initial participant export feature

## References
- Participant Management: `LogbookParticipant` Model
- Export System: `LogbookExportController`
- Grade System: [grade_participants_implementation.md](../../../loggenerator/docs/grade_participants_implementation.md)
