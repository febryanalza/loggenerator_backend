<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogbookTemplate;
use App\Models\LogbookData;

class HomeController extends Controller
{
    /**
     * Display the homepage with company profile
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Get dynamic statistics from database with real-time data
        $userCount = User::count();
        $templateCount = LogbookTemplate::count();
        $dataCount = LogbookData::count();
        
        // Additional real-time statistics using correct column names
        $activeUsersToday = User::whereDate('last_login', today())->count();
        $templatesThisMonth = LogbookTemplate::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)
                                           ->count();
        $entriesThisWeek = LogbookData::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        $companyData = [
            'name' => config('company.name', config('app.name', 'LogGenerator')),
            'tagline' => config('company.tagline', 'Sebuah Sistem untuk pencatatan kegiatan Pengabdian Masyarakat'),
            'description' => config('company.description', 'Loggenerator merupakan produk Program Equity Universitas Negeri Padang dengan Judul An Offline-First Collaborative Digital Logbook Application for Community Service Programs in Low Connectivity Regions'),
            'features' => [
                [
                    'icon' => '📱',
                    'title' => 'Desain Mobile-First',
                    'description' => 'Akses logbook Anda di mana saja dan kapan saja dengan aplikasi mobile kami yang responsif.'
                ],
                [
                    'icon' => '⚡',
                    'title' => 'Sinkronisasi Real-Time',
                    'description' => 'Sinkronisasi data instan di seluruh perangkat dan anggota tim.'
                ],
                [
                    'icon' => '🔒',
                    'title' => 'Aman & Terpercaya',
                    'description' => 'Keamanan tingkat perusahaan dengan kontrol akses berbasis peran dan riwayat audit.'
                ],
                [
                    'icon' => '📊',
                    'title' => 'Dashboard Analisis',
                    'description' => 'Hasilkan wawasan dari data logbook Anda dengan alat analisis yang kuat.'
                ],
                [
                    'icon' => '🎨',
                    'title' => 'Templat yang Dapat Disesuaikan',
                    'description' => 'Buat templat logbook khusus yang disesuaikan dengan kebutuhan spesifik Anda.'
                ],
                [
                    'icon' => '👥',
                    'title' => 'Kolaborasi Tim',
                    'description' => 'Memungkinkan kolaborasi yang lancar dengan manajemen izin dan notifikasi.'
                ]
            ],
            'app_links' => [
                'android' => config('company.android_app_url', 'https://play.google.com/store/apps/details?id=com.loggenerator.app'),
                'ios' => config('company.ios_app_url', 'https://apps.apple.com/app/loggenerator/id123456789')
            ],
            'contact' => [
                'email' => config('company.email', 'alzaqrifebryan@student.unp.ac.id'),
                'phone' => config('company.phone', '+62 812 6893 2502'),
                'address' => config('company.address', 'Padang, Sumatera Barat, Indonesia')
            ],
            'stats' => [
                'users' => $this->formatNumber($userCount),
                'logbooks' => $this->formatNumber($templateCount),
                'entries' => $this->formatNumber($dataCount),
                'uptime' => '99.9%'
            ],
            'real_time_stats' => [
                'active_users_today' => $activeUsersToday,
                'templates_this_month' => $templatesThisMonth,
                'entries_this_week' => $entriesThisWeek,
                'total_activity' => $userCount + $templateCount + $dataCount,
                'last_updated' => now()->format('d M Y, H:i:s')
            ]
        ];

        return view('welcome', compact('companyData'));
    }

    /**
     * Format number for display (K, M format)
     *
     * @param int $number
     * @return string
     */
    private function formatNumber($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }
        
        return (string) $number;
    }

    /**
     * Show about page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Show contact page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function contact()
    {
        return view('pages.contact');
    }
}
