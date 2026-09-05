@extends('layouts.student.dashboardstudent')

@section('title', $module->title . ' — Portal Belajar Siswa')

@push('styles')
<style>
    /* Prose styling for Uraian Materi */
    .materi-prose h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: .75rem; margin-top: 1.5rem; color: #0f172a; }
    .materi-prose h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1.25rem; color: #1e293b; }
    .materi-prose h3 { font-size: 1.125rem; font-weight: 700; margin-bottom: .5rem; margin-top: 1rem; color: #334155; }
    .materi-prose p { margin-bottom: .75rem; line-height: 1.75; }
    .materi-prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: .75rem; }
    .materi-prose li { margin-bottom: .25rem; line-height: 1.65; }
    .materi-prose img { max-width: 100%; height: auto; border-radius: .75rem; margin: 1rem auto; display: block; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .materi-prose table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: .875rem; }
    .materi-prose th, .materi-prose td { border: 1px solid #e2e8f0; padding: .5rem .75rem; text-align: left; }
    .materi-prose th { background: #f8fafc; font-weight: 700; color: #0f172a; }
    .materi-prose blockquote { border-left: 4px solid #0284c7; background: #f0f9ff; padding: .75rem 1rem; margin: 1rem 0; border-radius: 0 .5rem .5rem 0; font-style: italic; color: #0369a1; }
    .materi-prose hr { border: none; border-top: 2px solid #e2e8f0; margin: 1.5rem 0; }
    .materi-prose a { color: #0284c7; text-decoration: underline; }

    /* Active styling in learning syllabus */
    .sidebar-item-active {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px -2px rgba(79, 70, 229, 0.35);
    }
    .sidebar-item-active * {
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')

@php
    $readList = (array) ($readComponents ?? []);

    // Definisikan seluruh halaman aktivitas yang aktif secara berurutan
    $pagesList = [];

    // Bagian 1: Bagian Awal
    if ($module->isInfoComponentActive('kata_pengantar')) {
        $pagesList[] = ['id' => 'kata_pengantar', 'type' => 'read', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Kata Pengantar', 'icon' => '✏️', 'badge' => 'Pengantar', 'desc' => 'Prakata dan sambutan motivasi guru pengampu'];
    }
    if ($module->isInfoComponentActive('petunjuk_penggunaan')) {
        $pagesList[] = ['id' => 'petunjuk_penggunaan', 'type' => 'read', 'sec' => 1, 'sec_name' => '1. Bagian Awal', 'title' => 'Petunjuk Penggunaan', 'icon' => '💡', 'badge' => 'Panduan', 'desc' => 'Panduan langkah belajar mandiri peserta didik'];
    }

    // Bagian 2: Pendahuluan
    if ($module->isInfoComponentActive('tujuan_pembelajaran')) {
        $pagesList[] = ['id' => 'tujuan_pembelajaran', 'type' => 'read', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Tujuan & Capaian', 'icon' => '🎯', 'badge' => 'Capaian', 'desc' => 'Target kompetensi pembelajaran (CP & TP)'];
    }
    $hasPetaKonsep = !empty($informasiUmum['peta_konsep_text'])
        || !empty($informasiUmum['peta_konsep']['peta_konsep_text'])
        || !empty($informasiUmum['peta_konsep']['peta_konsep_image_path'])
        || (!empty($informasiUmum['peta_konsep']) && is_string($informasiUmum['peta_konsep']));
    if ($module->isInfoComponentActive('peta_konsep') && $hasPetaKonsep) {
        $pagesList[] = ['id' => 'peta_konsep', 'type' => 'read', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Peta Konsep Materi', 'icon' => '🗺️', 'badge' => 'Alur Materi', 'desc' => 'Diagram hierarki konsep materi kejuruan'];
    }
    $hasGlosarium = !empty($informasiUmum['glosarium']) && (
        (is_array($informasiUmum['glosarium']) && count($informasiUmum['glosarium']) > 0)
        || (is_string($informasiUmum['glosarium']) && trim($informasiUmum['glosarium']) !== '')
    );
    if ($module->isInfoComponentActive('glosarium') && $hasGlosarium) {
        $pagesList[] = ['id' => 'glosarium', 'type' => 'read', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Glosarium Istilah', 'icon' => '📖', 'badge' => 'Kamus', 'desc' => 'Kamus istilah teknis & konsep penting'];
    }
    if ($module->has_pre_test && $module->preTest) {
        $pagesList[] = ['id' => 'pre_test', 'type' => 'quiz', 'sec' => 2, 'sec_name' => '2. Pendahuluan', 'title' => 'Pre-test (Diagnostik)', 'icon' => '⚡', 'badge' => 'Kuis Awal', 'desc' => 'Tes diagnostik awal kemampuan siswa'];
    }

    // Bagian 3: Kegiatan Belajar
    if ($module->has_materi) {
        $pagesList[] = ['id' => 'materi', 'type' => 'read', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Uraian Materi & PPT', 'icon' => '📖', 'badge' => 'Materi Inti', 'desc' => 'Uraian teori mendalam & slide presentasi'];
    }
    if ($module->has_video) {
        $pagesList[] = ['id' => 'video', 'type' => 'submission', 'sec' => 3, 'sec_name' => '3. Kegiatan Belajar', 'title' => 'Video & Resume YouTube', 'icon' => '▶️', 'badge' => 'Multimedia', 'desc' => 'Video interaktif & penulisan resume intisari'];
    }

    // Bagian 4: Evaluasi & Praktik
    if ($module->has_embed) {
        $pagesList[] = ['id' => 'embed', 'type' => 'submission', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Simulator Embed Interaktif', 'icon' => '🎮', 'badge' => 'Praktik', 'desc' => 'Eksplorasi simulator & upload screenshot'];
    }
    if ($module->has_job_sheet) {
        $pagesList[] = ['id' => 'job_sheet', 'type' => 'submission', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Job Sheet Praktikum', 'icon' => '📑', 'badge' => 'Laboratorium', 'desc' => 'Panduan instruksi kerja & laporan PDF'];
    }
    if ($module->has_lkpd) {
        $pagesList[] = ['id' => 'lkpd', 'type' => 'submission', 'sec' => 4, 'sec_name' => '4. Evaluasi & Praktik', 'title' => 'Tugas LKPD Siswa', 'icon' => '📋', 'badge' => 'Penugasan', 'desc' => 'Lembar kerja peserta didik berbasis proyek'];
    }

    // Bagian 5: Bagian Akhir
    if ($module->has_post_test && $module->postTest) {
        $pagesList[] = ['id' => 'post_test', 'type' => 'quiz', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Post-test (Evaluasi Akhir)', 'icon' => '🏆', 'badge' => 'Uji Akhir', 'desc' => 'Evaluasi ketuntasan belajar akhir modul'];
    }
    if ($module->isInfoComponentActive('daftar_pustaka') && !empty($informasiUmum['daftar_pustaka']['daftar_pustaka'])) {
        $pagesList[] = ['id' => 'daftar_pustaka', 'type' => 'read', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Daftar Pustaka', 'icon' => '📚', 'badge' => 'Rujukan', 'desc' => 'Daftar referensi buku dan sumber materi'];
    }
    $pagesList[] = ['id' => 'rekap_nilai', 'type' => 'rekap', 'sec' => 5, 'sec_name' => '5. Bagian Akhir', 'title' => 'Rekapitulasi Nilai', 'icon' => '📊', 'badge' => 'Transparansi', 'desc' => 'Matriks transparansi skor evaluasi siswa'];

    // Status penyelesaian awal untuk tombol Mulai/Lanjut
    $hasPageParam = request()->has('page') || session('success') || session('error');
    $initialViewMode = $hasPageParam ? 'learn' : 'overview';
    $initialPage = request()->query('page', $pagesList[0]['id'] ?? 'kata_pengantar');

    // Status pengerjaan backend
    $isPreTestDone = (bool) ($studentResult && $studentResult->pre_test_score !== null);
    $isMateriDone = (bool) ($studentResult && $studentResult->isComponentRead('materi'));
    $isVideoDone = (bool) $videoSummary;
    $isEmbedDone = (bool) $embedSubmission;
    $isJobSheetDone = (bool) $jobSheetSubmission;
    $isLkpdDone = (bool) $lkpdSubmission;
    $isPostTestDone = (bool) ($studentResult && $studentResult->post_test_score !== null);

    // Tautan kembali ke daftar modul kelas spesifik
    $classSubjectModulesUrl = $module->class_id
        ? route('student.classes.subject', ['class' => $module->class_id, 'subject' => $module->subject_id])
        : route('student.modules.subject', $module->subject_id);
    $classNameText = $module->schoolClass->full_name ?? ($module->schoolClass->name ?? 'Kelas');
@endphp

<div class="w-full space-y-6"
     x-data="{
        viewMode: '{{ $initialViewMode }}',
        activePage: '{{ $initialPage }}',
        openSections: { 1: true, 2: true, 3: true, 4: true, 5: true },
        mobileDrawerOpen: false,
        searchGlosarium: '',
        csrfToken: '{{ csrf_token() }}',
        markReadUrl: '{{ route('student.modules.mark-read', $module) }}',
        readComponents: {{ json_encode($readList) }},
        pages: {{ json_encode($pagesList) }},
        serverStatus: {
            pre_test: {{ $isPreTestDone ? 'true' : 'false' }},
            materi: {{ $isMateriDone ? 'true' : 'false' }},
            video: {{ $isVideoDone ? 'true' : 'false' }},
            embed: {{ $isEmbedDone ? 'true' : 'false' }},
            job_sheet: {{ $isJobSheetDone ? 'true' : 'false' }},
            lkpd: {{ $isLkpdDone ? 'true' : 'false' }},
            post_test: {{ $isPostTestDone ? 'true' : 'false' }}
        },

        showPreRetakeForm: false,
        showPreHistory: false,
        showPostRetakeForm: false,
        showPostHistory: false,

        hasScrolledToEnd: false,
        scrollObserver: null,

        get isCurrentPageReading() {
            const p = this.pages.find(item => item.id === this.activePage);
            return p ? p.type === 'read' : true;
        },

        get isTakingPreTest() {
            @if($module->has_pre_test && $module->preTest)
                return !{{ $isPreTestDone ? 'true' : 'false' }} || this.showPreRetakeForm;
            @else
                return false;
            @endif
        },
        get isTakingPostTest() {
            @if($module->has_post_test && $module->postTest)
                return !{{ $isPostTestDone ? 'true' : 'false' }} || this.showPostRetakeForm;
            @else
                return false;
            @endif
        },
        get isTakingTest() {
            if (this.viewMode !== 'learn') return false;
            if (this.activePage === 'pre_test') return this.isTakingPreTest;
            if (this.activePage === 'post_test') return this.isTakingPostTest;
            return false;
        },
        init() {
            this.$watch('isTakingTest', val => {
                window.dispatchEvent(new CustomEvent('set-sidebar-open', { detail: !val && window.innerWidth >= 1024 }));
            });
            this.$nextTick(() => {
                if (this.isTakingTest) {
                    window.dispatchEvent(new CustomEvent('set-sidebar-open', { detail: false }));
                }
            });

            // Sinkronkan mode belajar ke mobile-nav di layout
            this.$watch('viewMode', val => {
                window.dispatchEvent(new CustomEvent('toggle-learn-mode', { detail: val === 'learn' }));
                if (val === 'learn') {
                    this.initScrollDetection();
                }
            });

            this.$watch('activePage', () => {
                this.initScrollDetection();
            });

            // Inisialisasi awal
            window.dispatchEvent(new CustomEvent('toggle-learn-mode', { detail: this.viewMode === 'learn' }));
            if (this.viewMode === 'learn') {
                this.initScrollDetection();
            }

            // Global scroll listener sebagai backup deteksi scroll
            const onScrollHandler = () => {
                if (!this.hasScrolledToEnd && this.viewMode === 'learn') {
                    this.checkScrollPosition();
                }
            };
            window.addEventListener('scroll', onScrollHandler, { passive: true });
            const scrollableMain = document.querySelector('.overflow-y-auto');
            if (scrollableMain) {
                scrollableMain.addEventListener('scroll', onScrollHandler, { passive: true });
            }
        },

        initScrollDetection() {
            // Jika halaman sudah selesai dibaca, langsung buka tombol tengah
            if (this.isCompleted(this.activePage)) {
                this.hasScrolledToEnd = true;
                return;
            }

            // Jika bukan halaman tipe 'read' (misal kuis/tugas), tidak perlu deteksi scroll bacaan
            if (!this.isCurrentPageReading) {
                this.hasScrolledToEnd = true;
                return;
            }

            this.hasScrolledToEnd = false;

            this.$nextTick(() => {
                if (this.scrollObserver) {
                    this.scrollObserver.disconnect();
                }

                const allSentinels = Array.from(document.querySelectorAll('.reading-end-sentinel'));
                const currentSentinels = allSentinels.filter(el => el.getAttribute('data-page') === this.activePage);
                if (currentSentinels.length) {
                    this.scrollObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.hasScrolledToEnd = true;
                            }
                        });
                    }, {
                        root: null,
                        threshold: 0.05,
                        rootMargin: '0px 0px 80px 0px'
                    });

                    currentSentinels.forEach(el => this.scrollObserver.observe(el));
                }

                // Cek apakah konten sudah cukup pendek dan langsung terlihat di layar
                setTimeout(() => {
                    this.checkScrollPosition();
                }, 350);
            });
        },

        checkScrollPosition() {
            if (this.hasScrolledToEnd) return;
            const allSentinels = Array.from(document.querySelectorAll('.reading-end-sentinel'));
            const sentinel = allSentinels.find(el => el.getAttribute('data-page') === this.activePage);
            if (sentinel && sentinel.offsetParent !== null) {
                const rect = sentinel.getBoundingClientRect();
                const vh = window.innerHeight || document.documentElement.clientHeight;
                if (rect.top <= vh + 80) {
                    this.hasScrolledToEnd = true;
                }
            }
        },

        markCurrentAsCompleted() {
            this.markAsRead(this.activePage);
            this.hasScrolledToEnd = true;
        },
        
        get totalActiveComps() {
            return {{ $totalActive }};
        },
        get computedCompletedTasks() {
            let count = 0;
            @if($module->pre_test_active)
                if (this.isCompleted('pre_test')) count++;
            @endif
            @if($module->materi_active)
                if (this.isCompleted('materi')) count++;
            @endif
            @if($module->video_active)
                if (this.isCompleted('video')) count++;
            @endif
            @if($module->embed_active)
                if (this.isCompleted('embed')) count++;
            @endif
            @if($module->job_sheet_active)
                if (this.isCompleted('job_sheet')) count++;
            @endif
            @if($module->lkpd_active)
                if (this.isCompleted('lkpd')) count++;
            @endif
            @if($module->post_test_active)
                if (this.isCompleted('post_test')) count++;
            @endif
            return count;
        },
        get computedProgressPercent() {
            if (this.totalActiveComps <= 0) return 100;
            return Math.min(100, Math.round((this.computedCompletedTasks / this.totalActiveComps) * 100));
        },
        
        get currentIndex() {
            return this.pages.findIndex(p => p.id === this.activePage);
        },
        get currentPage() {
            return this.pages[this.currentIndex] || this.pages[0];
        },
        get prevPage() {
            return this.currentIndex > 0 ? this.pages[this.currentIndex - 1] : null;
        },
        get nextPage() {
            return this.currentIndex < this.pages.length - 1 ? this.pages[this.currentIndex + 1] : null;
        },

        // Memeriksa apakah suatu halaman sudah selesai dikerjakan / dibaca
        isCompleted(pageId) {
            if (pageId === 'pre_test') return this.serverStatus.pre_test;
            if (pageId === 'materi') return this.serverStatus.materi || this.readComponents.includes('materi');
            if (pageId === 'video') return this.serverStatus.video;
            if (pageId === 'embed') return this.serverStatus.embed;
            if (pageId === 'job_sheet') return this.serverStatus.job_sheet;
            if (pageId === 'lkpd') return this.serverStatus.lkpd;
            if (pageId === 'post_test') return this.serverStatus.post_test;
            if (pageId === 'rekap_nilai') return true;
            return this.readComponents.includes(pageId);
        },

        // Memeriksa apakah suatu halaman sudah terbuka berdasarkan alur sekuensial
        isUnlocked(pageId) {
            const idx = this.pages.findIndex(p => p.id === pageId);
            if (idx <= 0) return true; // Halaman pertama (Kata Pengantar / Orientasi) selalu terbuka
            
            // Halaman terbuka hanya jika seluruh halaman sebelumnya sudah diselesaikan
            for (let i = 0; i < idx; i++) {
                if (!this.isCompleted(this.pages[i].id)) {
                    return false;
                }
            }
            return true;
        },

        // Menemukan halaman pertama yang belum selesai dan sudah terbuka
        getFirstActionablePage() {
            for (let i = 0; i < this.pages.length; i++) {
                if (!this.isCompleted(this.pages[i].id)) {
                    return this.pages[i].id;
                }
            }
            return this.pages[0]?.id || 'kata_pengantar';
        },

        // Mulai / Lanjut Belajar dari halaman yang terbuka
        startLearning(targetPageId) {
            let target = targetPageId || this.getFirstActionablePage();
            if (!this.isUnlocked(target)) {
                target = this.getFirstActionablePage();
            }
            this.goToPage(target);
        },

        // Berpindah ke halaman tertentu jika sudah terbuka
        goToPage(pageId) {
            if (!this.isUnlocked(pageId)) {
                alert('⚠️ Halaman ini masih terkunci! Silakan baca dan selesaikan langkah sebelumnya terlebih dahulu.');
                return;
            }
            this.activePage = pageId;
            const target = this.pages.find(p => p.id === pageId);
            if (target) {
                this.openSections[target.sec] = true;
            }
            this.viewMode = 'learn';
            this.mobileDrawerOpen = false;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            const mainScroll = document.querySelector('.overflow-y-auto');
            if (mainScroll) mainScroll.scrollTo({ top: 0, behavior: 'smooth' });
        },

        // Menandai halaman selesai dibaca tanpa berpindah halaman
        markAsRead(currentCompId) {
            if (!this.readComponents.includes(currentCompId)) {
                this.readComponents.push(currentCompId);
            }

            // Kirim status baca ke server secara asinkron (AJAX)
            fetch(this.markReadUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ component: currentCompId })
            }).catch(err => console.error('Error marking read:', err));
        },

        // Tombol lompat ke halaman baru setelah membaca: Menandai selesai dibaca dan buka langkah selanjutnya
        markAsReadAndGoNext(currentCompId, nextCompId) {
            if (!this.readComponents.includes(currentCompId)) {
                this.readComponents.push(currentCompId);
            }

            // Kirim status baca ke server secara asinkron (AJAX)
            fetch(this.markReadUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ component: currentCompId })
            }).catch(err => console.error('Error marking read:', err));

            // Lompat langsung ke halaman baru jika ada
            if (nextCompId) {
                this.goToPage(nextCompId);
            } else {
                this.viewMode = 'overview';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        toggleSection(secNum) {
            this.openSections[secNum] = !this.openSections[secNum];
        },

        // ══ MODAL KONFIRMASI SUBMIT & PEMBATALAN TUGAS ══
        submitModal: {
            open: false,
            submitting: false,
            title: '',
            description: '',
            accentColor: 'emerald',
            warningText: '',
            confirmLabel: 'Kirim Sekarang',
            category: 'Konfirmasi Pengiriman',
            loadingLabel: 'Mengirim...',
            formEl: null,
        },
        openSubmitModal(config, formEl) {
            this.submitModal.open = true;
            this.submitModal.submitting = false;
            this.submitModal.title = config.title || 'Konfirmasi Pengiriman';
            this.submitModal.description = config.description || 'Apakah Anda yakin ingin mengirimkan jawaban ini?';
            this.submitModal.accentColor = config.accentColor || 'emerald';
            this.submitModal.warningText = config.warningText || '';
            this.submitModal.confirmLabel = config.confirmLabel || 'Kirim Sekarang';
            this.submitModal.category = config.category || 'Konfirmasi Pengiriman';
            this.submitModal.loadingLabel = config.loadingLabel || 'Mengirim...';
            this.submitModal.formEl = formEl;
            document.body.style.overflow = 'hidden';
        },
        openCancelModal(config, formEl) {
            this.submitModal.open = true;
            this.submitModal.submitting = false;
            this.submitModal.title = config.title || 'Batalkan Kiriman Tugas?';
            this.submitModal.description = config.description || 'Apakah Anda yakin ingin membatalkan kiriman tugas ini untuk mengunggah atau mengedit ulang?';
            this.submitModal.accentColor = 'rose';
            this.submitModal.warningText = config.warningText || 'Tugas yang dibatalkan akan direset dan harus dikirimkan kembali agar dapat dinilai guru.';
            this.submitModal.confirmLabel = config.confirmLabel || 'Ya, Batalkan Tugas';
            this.submitModal.category = 'Konfirmasi Pembatalan';
            this.submitModal.loadingLabel = 'Membatalkan...';
            this.submitModal.formEl = formEl;
            document.body.style.overflow = 'hidden';
        },
        closeSubmitModal() {
            if (this.submitModal.submitting) return;
            this.submitModal.open = false;
            this.submitModal.formEl = null;
            document.body.style.overflow = '';
        },
        confirmSubmit() {
            if (!this.submitModal.formEl || this.submitModal.submitting) return;
            this.submitModal.submitting = true;
            this.submitModal.formEl.submit();
        }
     }">

    {{-- ═══ 1. STICKY TOP HEADER & BREADCRUMB ═══ --}}
    <div x-show="!isTakingTest">
        @include('pages.student.modules.partials.header')
    </div>

    {{-- ═══ VIEW 1: TAMPILAN AWAL DETAIL MODUL SISWA (FULL WIDTH CARD) ═══ --}}
    @include('pages.student.modules.partials.overview')

    {{-- ═══ VIEW 2: MODE BELAJAR INTERAKTIF (WORKSPACE AKTIVITAS PEMBELAJARAN FULL WIDTH) ═══ --}}
    <div x-show="viewMode === 'learn'" x-cloak class="w-full space-y-6 pb-24 lg:pb-0">

        {{-- 1. Kata Pengantar --}}
        @include('pages.student.modules.partials.activities.kata-pengantar')

        {{-- 2. Petunjuk Penggunaan --}}
        @include('pages.student.modules.partials.activities.petunjuk-penggunaan')

        {{-- 3. Tujuan Pembelajaran --}}
        @include('pages.student.modules.partials.activities.tujuan-pembelajaran')

        {{-- 4. Peta Konsep --}}
        @include('pages.student.modules.partials.activities.peta-konsep')

        {{-- 5. Glosarium --}}
        @include('pages.student.modules.partials.activities.glosarium')

        {{-- 6. Pre-test --}}
        @include('pages.student.modules.partials.activities.pre-test')

        {{-- 7. Uraian Materi --}}
        @include('pages.student.modules.partials.activities.materi')

        {{-- 8. Video YouTube & Resume --}}
        @include('pages.student.modules.partials.activities.video')

        {{-- 9. Simulator Embed --}}
        @include('pages.student.modules.partials.activities.embed')

        {{-- 10. Job Sheet Praktikum --}}
        @include('pages.student.modules.partials.activities.job-sheet')

        {{-- 11. Tugas LKPD --}}
        @include('pages.student.modules.partials.activities.lkpd')

        {{-- 12. Post-test --}}
        @include('pages.student.modules.partials.activities.post-test')

        {{-- 13. Daftar Pustaka --}}
        @include('pages.student.modules.partials.activities.daftar-pustaka')

        {{-- 14. Rekapitulasi Nilai Siswa --}}
        @include('pages.student.modules.partials.activities.rekap-nilai')

        {{-- 3. Bottom Sequential Navigation Bar --}}
        <div x-show="!isTakingTest">
            @include('pages.student.modules.partials.bottom-nav')
        </div>

    </div>

    {{-- MODAL KONFIRMASI PENGIRIMAN JAWABAN (UNIVERSAL) --}}
    @include('pages.student.modules.partials.modal-submit')

</div>

@endsection
