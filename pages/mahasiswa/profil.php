<?php
declare(strict_types=1);

require_once __DIR__ . '/../../app/core/Auth.php';
require_once __DIR__ . '/../../app/controllers/ProfileController.php';

$currentUser = Auth::guard([ROLE_MAHASISWA]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new ProfileController())->handle();
}

$userModel = new UserModel();
$user = $userModel->findById((int) ($currentUser['id_user'] ?? 0));

if ($user === null) {
    Auth::logout();
    Helper::flash('error', 'Data akun tidak ditemukan. Silakan masuk kembali.');
    Helper::redirect('/pages/auth/login.php');
}

$flashes = Helper::allFlash();

$nama = (string) ($user['nama'] ?? '');
$nimNip = (string) ($user['nim_nip'] ?? '');
$email = (string) ($user['email'] ?? '');
$noHp = (string) ($user['no_hp'] ?? '');
$namaRole = (string) ($user['nama_role'] ?? ROLE_MAHASISWA);
$statusAkun = (string) ($user['status_akun'] ?? STATUS_AKUN_AKTIF);
$isAktif = $statusAkun === STATUS_AKUN_AKTIF;

// Inisial avatar: "Muhammad Rafi" -> "MR".
$nameParts = preg_split('/\s+/u', trim($nama)) ?: [];
$initials = '';
foreach ($nameParts as $part) {
    if ($part === '') {
        continue;
    }
    $initials .= function_exists('mb_substr')
        ? mb_substr($part, 0, 1, 'UTF-8')
        : substr($part, 0, 1);
}
$initials = function_exists('mb_strtoupper')
    ? mb_strtoupper($initials, 'UTF-8')
    : strtoupper($initials);
$initials = function_exists('mb_substr')
    ? mb_substr($initials, 0, 2, 'UTF-8')
    : substr($initials, 0, 2);
$initials = $initials === '' ? '?' : $initials;

// Format tanggal pendaftaran dalam bahasa Indonesia.
$createdAtRaw = (string) ($user['created_at'] ?? '');
$createdAtTs = $createdAtRaw !== '' ? strtotime($createdAtRaw) : false;
$bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
];
$createdAtLabel = $createdAtTs !== false
    ? date('d', $createdAtTs) . ' ' . $bulan[(int) date('n', $createdAtTs)] . ' ' . date('Y', $createdAtTs)
    : '-';

$inputClass = 'w-full h-[42px] px-3.5 bg-surface-container-low focus:bg-surface border border-border rounded-lg font-body-md text-body-md text-on-surface placeholder:text-text-muted outline-none transition-all duration-150 focus:shadow-[0_0_0_2px_rgba(118,86,61,0.2)] focus:border-primary';
$readonlyClass = $inputClass . ' bg-surface-muted text-text-secondary cursor-not-allowed';
$primaryButtonClass = 'inline-flex items-center justify-center gap-2 h-[42px] px-5 rounded-lg bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md shadow-sm transition-all duration-150 active:scale-[0.99]';
$secondaryButtonClass = 'inline-flex items-center justify-center gap-2 h-[42px] px-5 rounded-lg bg-secondary hover:bg-primary text-on-primary font-label-md text-label-md shadow-sm transition-all duration-150 active:scale-[0.99]';
$cancelButtonClass = 'inline-flex items-center justify-center gap-2 h-[42px] px-5 rounded-lg bg-surface border border-border hover:bg-surface-muted text-on-surface font-label-md text-label-md transition-colors';
?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
<link rel="stylesheet" href="../../assets/css/style.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="../../assets/js/config/tailwind-config.js"></script><title>Profil - SIPERUSAK</title></head>
<body class="bg-canvas text-on-surface font-body-md text-body-md antialiased" data-role="mahasiswa" data-nav="profil" data-crumbs="Portal Mahasiswa|Profil">
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-[230px] bg-surface border-r border-border z-50 hidden md:flex flex-col justify-between select-none"></aside>
<div class="pl-0 md:pl-[230px]">
<header id="topbar" class="fixed top-0 left-0 md:left-[230px] right-0 h-16 bg-surface border-b border-border z-40 px-4 md:px-space-lg flex items-center justify-between"></header>
<main class="w-full pt-16 min-h-screen bg-canvas">
<div class="w-full px-4 md:px-margin-desktop py-space-lg md:py-space-xl flex flex-col items-center">
  <div class="w-full max-w-4xl flex flex-col gap-space-lg">

    <div class="flex flex-col gap-1">
      <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Profil</h1>
      <p class="font-body-md text-body-md text-text-secondary">Informasi akun dan identitas mahasiswa.</p>
    </div>

    <?php if ($flashes !== []): ?>
      <div class="w-full flex flex-col gap-3">
        <?php foreach ($flashes as $flash): ?>
          <?php
            $flashType = in_array(($flash['type'] ?? 'info'), ['success', 'error', 'info'], true)
                ? $flash['type']
                : 'info';
            $flashStyles = [
                'success' => ['bg-status-completed-bg', 'text-status-completed-text', 'border-status-completed-border', 'check_circle'],
                'error'   => ['bg-status-error-bg', 'text-status-error-text', 'border-status-error-border', 'error'],
                'info'    => ['bg-status-verified-bg', 'text-status-verified-text', 'border-status-verified-border', 'info'],
            ];
            [$flashBg, $flashText, $flashBorder, $flashIcon] = $flashStyles[$flashType];
          ?>
          <div class="w-full rounded-lg border px-4 py-3 flex items-start gap-2.5 shadow-sm <?= $flashBg ?> <?= $flashText ?> <?= $flashBorder ?>">
            <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5"><?= $flashIcon ?></span>
            <p class="font-body-md text-body-md leading-relaxed"><?= Helper::e($flash['message'] ?? '') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="bg-surface rounded-lg shadow-sm flex flex-col overflow-hidden">

      <!-- Identitas -->
      <div class="p-space-lg md:p-space-xl flex flex-col sm:flex-row sm:items-center justify-between gap-space-md bg-surface-container-low/40">
        <div class="flex items-center gap-space-md">
          <div class="w-16 h-16 rounded-full bg-secondary-container/50 flex items-center justify-center flex-shrink-0 select-none">
            <span class="font-headline-lg text-headline-lg font-bold text-primary-container"><?= Helper::e($initials) ?></span>
          </div>
          <div class="flex flex-col min-w-0">
            <h2 class="font-headline-sm text-headline-sm text-on-surface font-semibold truncate leading-tight"><?= Helper::e($nama) ?></h2>
            <span class="font-body-md text-body-md text-text-secondary mt-0.5">NIM <span class="tabular-nums"><?= Helper::e($nimNip) ?></span></span>
          </div>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-center">
          <span class="inline-flex items-center px-3 py-1 rounded font-label-sm text-label-sm font-semibold bg-surface-muted text-primary-container"><?= Helper::e($namaRole) ?></span>
          <?php if ($isAktif): ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded font-label-sm text-label-sm font-semibold bg-status-completed-bg text-status-completed-text">
              <span class="w-1.5 h-1.5 rounded-full bg-status-completed-text"></span>Aktif
            </span>
          <?php else: ?>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded font-label-sm text-label-sm font-semibold bg-status-error-bg text-status-error-text">
              <span class="w-1.5 h-1.5 rounded-full bg-status-error-text"></span>Nonaktif
            </span>
          <?php endif; ?>
        </div>
      </div>

      <div class="h-[1px] w-full bg-surface-container"></div>

      <!-- Rincian data -->
      <div class="p-space-lg md:p-space-xl flex flex-col gap-space-md">
        <div class="flex items-center gap-2 pb-2">
          <span class="material-symbols-outlined text-[20px] text-text-muted">badge</span>
          <span class="font-label-md text-label-md text-on-surface uppercase tracking-wider">Rincian Data Pengguna</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nama Lengkap</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate"><?= Helper::e($nama) ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nomor Induk Mahasiswa (NIM)</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate tabular-nums"><?= Helper::e($nimNip) ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Email Kampus / Pengguna</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate"><?= Helper::e($email) ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Nomor Telepon</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate tabular-nums"><?= $noHp !== '' ? Helper::e($noHp) : '-' ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Peran Akun</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate"><?= Helper::e($namaRole) ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Status Akun</span>
            <span class="font-body-strong text-body-strong <?= $isAktif ? 'text-status-completed-text' : 'text-status-error-text' ?>"><?= $isAktif ? 'Aktif (Terdaftar Resmi)' : 'Nonaktif' ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">Terdaftar Sejak</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate tabular-nums"><?= Helper::e($createdAtLabel) ?></span>
          </div>
          <div class="p-space-md rounded bg-canvas flex flex-col justify-center">
            <span class="font-caption text-caption text-text-secondary mb-1">ID Akun</span>
            <span class="font-body-strong text-body-strong text-on-surface truncate tabular-nums">#<?= (int) ($user['id_user'] ?? 0) ?></span>
          </div>
        </div>
      </div>

      <div class="h-[1px] w-full bg-surface-container"></div>

      <!-- Catatan -->
      <div class="p-space-md md:p-space-lg bg-surface-muted/60 flex items-start gap-space-sm text-text-secondary">
        <span class="material-symbols-outlined text-[20px] text-primary-container flex-shrink-0 mt-0.5">info</span>
        <div class="flex flex-col text-left">
          <span class="font-label-sm text-label-sm text-on-surface mb-0.5">Catatan Pengguna</span>
          <p class="font-caption text-caption text-text-secondary leading-relaxed">Nama, email, dan nomor telepon dapat diperbarui langsung melalui formulir di bawah. NIM bersifat read-only karena terikat dengan data akademik. Untuk perubahan data akademik, silakan menghubungi Bagian Administrasi Akademik.</p>
        </div>
      </div>
    </div>

    <!-- Edit Profil -->
    <div class="bg-surface rounded-lg shadow-sm flex flex-col overflow-hidden">
      <div class="p-space-lg md:p-space-xl border-b border-border flex items-start gap-space-sm">
        <span class="material-symbols-outlined text-[22px] text-primary-container flex-shrink-0 mt-0.5">edit</span>
        <div class="flex flex-col">
          <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Edit Profil</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5">Perbarui nama, email, dan nomor telepon yang digunakan pada akun Anda.</p>
        </div>
      </div>
      <form id="profile-form" method="post" action="profil.php" class="p-space-lg md:p-space-xl flex flex-col gap-space-md">
        <?= Helper::csrfField() ?>
        <input type="hidden" name="action" value="update_profile">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
          <div class="flex flex-col gap-1.5 md:col-span-2">
            <label class="font-label-md text-label-md text-on-surface" for="profile-nama">Nama Lengkap</label>
            <input
              id="profile-nama"
              name="nama"
              type="text"
              required
              maxlength="100"
              autocomplete="name"
              placeholder="Masukkan nama lengkap"
              value="<?= Helper::e($nama) ?>"
              class="<?= $inputClass ?>"
            />
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface" for="profile-nim">NIM (Read-only)</label>
            <input
              id="profile-nim"
              type="text"
              readonly
              aria-readonly="true"
              value="<?= Helper::e($nimNip) ?>"
              class="<?= $readonlyClass ?> tabular-nums"
            />
            <span class="font-caption text-caption text-text-muted">NIM tidak dapat diubah melalui halaman ini.</span>
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface" for="profile-email">Email Kampus / Pengguna</label>
            <input
              id="profile-email"
              name="email"
              type="email"
              required
              maxlength="120"
              autocomplete="email"
              placeholder="nama@student.kampus.ac.id"
              value="<?= Helper::e($email) ?>"
              class="<?= $inputClass ?>"
            />
          </div>

          <div class="flex flex-col gap-1.5 md:col-span-2">
            <label class="font-label-md text-label-md text-on-surface" for="profile-no-hp">Nomor WhatsApp / Telepon</label>
            <input
              id="profile-no-hp"
              name="no_hp"
              type="tel"
              maxlength="20"
              inputmode="tel"
              autocomplete="tel"
              placeholder="081234567890"
              value="<?= Helper::e($noHp) ?>"
              class="<?= $inputClass ?> tabular-nums"
            />
            <span class="font-caption text-caption text-text-muted">Gunakan angka, spasi, tanda hubung, tanda plus, atau tanda kurung. Maksimal 20 karakter.</span>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2 pt-1">
          <a href="profil.php" class="<?= $cancelButtonClass ?>">
            <span class="material-symbols-outlined text-[18px]">close</span>
            <span>Batal</span>
          </a>
          <button type="submit" class="<?= $primaryButtonClass ?>">
            <span class="material-symbols-outlined text-[18px]">save</span>
            <span>Simpan Perubahan</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Ganti Kata Sandi -->
    <div class="bg-surface rounded-lg shadow-sm flex flex-col overflow-hidden">
      <div class="p-space-lg md:p-space-xl border-b border-border flex items-start gap-space-sm">
        <span class="material-symbols-outlined text-[22px] text-primary-container flex-shrink-0 mt-0.5">lock_reset</span>
        <div class="flex flex-col">
          <h3 class="font-headline-sm text-headline-sm text-on-surface font-semibold">Ganti Kata Sandi</h3>
          <p class="font-caption text-caption text-text-secondary mt-0.5">Gunakan kata sandi baru minimal 8 karakter dan konfirmasi yang sama.</p>
        </div>
      </div>
      <form id="password-form" method="post" action="profil.php" class="p-space-lg md:p-space-xl flex flex-col gap-space-md">
        <?= Helper::csrfField() ?>
        <input type="hidden" name="action" value="change_password">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface" for="old-password">Kata Sandi Lama</label>
            <input
              id="old-password"
              name="old_password"
              type="password"
              required
              autocomplete="current-password"
              placeholder="Kata sandi saat ini"
              class="<?= $inputClass ?>"
            />
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface" for="new-password">Kata Sandi Baru</label>
            <input
              id="new-password"
              name="new_password"
              type="password"
              required
              minlength="8"
              autocomplete="new-password"
              placeholder="Minimal 8 karakter"
              class="<?= $inputClass ?>"
            />
          </div>

          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface" for="confirm-password">Konfirmasi Kata Sandi Baru</label>
            <input
              id="confirm-password"
              name="confirm_password"
              type="password"
              required
              minlength="8"
              autocomplete="new-password"
              placeholder="Ulangi kata sandi baru"
              class="<?= $inputClass ?>"
            />
          </div>
        </div>

        <div class="flex justify-end pt-1">
          <button type="submit" class="<?= $secondaryButtonClass ?>">
            <span class="material-symbols-outlined text-[18px]">lock</span>
            <span>Perbarui Kata Sandi</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</main>
</div>
<?= Auth::userScript() ?>
<script src="../../assets/js/components/layout.js?v=2"></script></body></html>
