<?php
declare(strict_types=1);

$page_title = $page_title ?? 'Tap N Bite';
$page_description = $page_description ?? '';
$page_keywords = $page_keywords ?? '';
$nav_active = $nav_active ?? '';
$contact_href = $contact_href ?? '/#contact';
$body_class = $body_class ?? 'bg-white text-slate-900 antialiased';
$extra_head = $extra_head ?? '';

$h = static function (string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
};

$navDesktop = static function (string $page) use ($nav_active): string {
    return $nav_active === $page
        ? 'text-sm font-medium text-brand-600'
        : 'text-sm font-medium text-slate-700 hover:text-brand-600 transition-colors';
};

$navMobile = static function (string $page) use ($nav_active): string {
    return $nav_active === $page
        ? 'rounded-xl px-4 py-3 text-lg font-semibold text-brand-600 bg-orange-100/60'
        : 'rounded-xl px-4 py-3 text-lg font-semibold text-slate-700 hover:bg-orange-100/60 hover:text-brand-600';
};
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon" />
    <title><?php echo $h($page_title); ?></title>
<?php if ($page_description !== '') : ?>
    <meta name="description" content="<?php echo $h($page_description); ?>" />
<?php endif; ?>
<?php if ($page_keywords !== '') : ?>
    <meta name="keywords" content="<?php echo $h($page_keywords); ?>" />
<?php endif; ?>
    <?php echo $extra_head; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              brand: {
                50: "#fff7ed",
                100: "#ffedd5",
                200: "#fed7aa",
                300: "#fdba74",
                400: "#fb923c",
                500: "#f97316",
                600: "#ea580c",
                700: "#c2410c",
                800: "#9a3412",
              },
            },
            animation: {
              'fade-in': 'fadeIn 0.6s ease-in-out',
              'slide-up': 'slideUp 0.6s ease-out',
              'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
              fadeIn: {
                '0%': { opacity: '0' },
                '100%': { opacity: '1' },
              },
              slideUp: {
                '0%': { transform: 'translateY(20px)', opacity: '0' },
                '100%': { transform: 'translateY(0)', opacity: '1' },
              },
              float: {
                '0%, 100%': { transform: 'translateY(0px)' },
                '50%': { transform: 'translateY(-20px)' },
              },
            },
          },
        },
      };
    </script>
  </head>
  <body class="<?php echo $h($body_class); ?>">
    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-orange-100/70 bg-orange-50/95 backdrop-blur-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          <a href="/" class="flex items-center">
            <img src="/images/logo.png" alt="Tap N Bite" class="h-12 sm:h-14">
          </a>
          <nav class="hidden md:flex items-center gap-8" aria-label="Main">
            <a href="/features" class="<?php echo $h($navDesktop('features')); ?>">Features</a>
            <a href="/pricing" class="<?php echo $h($navDesktop('pricing')); ?>">Pricing</a>
            <a href="/about" class="<?php echo $h($navDesktop('about')); ?>">About</a>
            <a
              href="<?php echo $h($contact_href); ?>"
              class="inline-flex items-center px-5 py-2.5 rounded-full bg-brand-500 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-all hover:shadow-xl hover:shadow-brand-500/40"
            >
              Get Started
            </a>
          </nav>
          <button
            type="button"
            id="nav-toggle"
            class="inline-flex items-center justify-center rounded-lg p-2.5 text-slate-700 hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 md:hidden"
            aria-expanded="false"
            aria-controls="mobile-nav"
          >
            <span class="sr-only">Toggle menu</span>
            <svg id="nav-icon-open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="nav-icon-close" class="hidden h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </header>
    <div id="mobile-nav" class="fixed inset-0 z-50 pointer-events-none md:hidden" aria-hidden="true">
      <div id="mobile-nav-backdrop" class="absolute inset-0 bg-slate-900/45 opacity-0 transition-opacity duration-300 ease-out"></div>
      <div id="mobile-nav-panel" class="absolute inset-0 -translate-x-full bg-gradient-to-br from-orange-50 via-white to-red-50 transition-transform duration-300 ease-out">
        <button
          type="button"
          id="mobile-nav-close"
          class="absolute right-5 top-5 inline-flex items-center justify-center rounded-xl border-2 border-brand-500/80 bg-orange-50/90 p-2.5 text-slate-700 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
          aria-label="Close menu"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <div class="flex h-full flex-col px-6 pb-8 pt-6 sm:px-8">
          <a href="/" class="inline-flex items-center pr-16">
            <img src="/images/logo.png" alt="Tap N Bite" class="h-11">
          </a>
          <nav class="mt-8 flex flex-col gap-2" aria-label="Mobile">
            <a href="/features" class="<?php echo $h($navMobile('features')); ?>">Features</a>
            <a href="/pricing" class="<?php echo $h($navMobile('pricing')); ?>">Pricing</a>
            <a href="/about" class="<?php echo $h($navMobile('about')); ?>">About</a>
          </nav>
          <a
            href="<?php echo $h($contact_href); ?>"
            class="mt-auto inline-flex items-center justify-center rounded-full bg-brand-500 px-6 py-3 text-base font-semibold text-white shadow-lg shadow-brand-500/30 hover:bg-brand-600"
          >
            Get Started
          </a>
        </div>
      </div>
    </div>
    <script>
      (function () {
        var btn = document.getElementById('nav-toggle');
        var menu = document.getElementById('mobile-nav');
        var backdrop = document.getElementById('mobile-nav-backdrop');
        var panel = document.getElementById('mobile-nav-panel');
        var closeBtn = document.getElementById('mobile-nav-close');
        var openIcon = document.getElementById('nav-icon-open');
        var closeIcon = document.getElementById('nav-icon-close');
        if (!btn || !menu || !backdrop || !panel || !closeBtn || !openIcon || !closeIcon) return;
        function setOpen(open) {
          menu.classList.toggle('pointer-events-none', !open);
          menu.setAttribute('aria-hidden', String(!open));
          backdrop.classList.toggle('opacity-0', !open);
          backdrop.classList.toggle('opacity-100', open);
          panel.classList.toggle('-translate-x-full', !open);
          panel.classList.toggle('translate-x-0', open);
          btn.setAttribute('aria-expanded', String(open));
          openIcon.classList.toggle('hidden', open);
          closeIcon.classList.toggle('hidden', !open);
          document.body.classList.toggle('overflow-hidden', open);
        }
        btn.addEventListener('click', function () {
          setOpen(menu.classList.contains('pointer-events-none'));
        });
        closeBtn.addEventListener('click', function () {
          setOpen(false);
        });
        backdrop.addEventListener('click', function () {
          setOpen(false);
        });
        menu.querySelectorAll('a').forEach(function (link) {
          link.addEventListener('click', function () {
            setOpen(false);
          });
        });
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && !menu.classList.contains('pointer-events-none')) setOpen(false);
        });
        window.addEventListener('resize', function () {
          if (window.matchMedia('(min-width: 768px)').matches) setOpen(false);
        });
      })();
    </script>
