@load('layout.scss')

<!-- Header -->
<header class="rb-header" id="header">
  <div class="header-inner">
    <div class="header-left">
      <button class="mobile-menu-toggle button" id="mobileMenuBtn">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

      <h1 class="logo">
        <a href="/">
          @if(!empty($layout_info->logo_img))
          <img src="{{ $layout_info->logo_img }}" alt="{{ $layout_info->logo_text }}" class="logo-pc">
          <img src="{{ $layout_info->logo_img }}" alt="{{ $layout_info->logo_text }}" class="logo-mo">
          @else
          <span class="logo-pc">{{ $layout_info->logo_text ?? 'RootSkin' }}</span>
          <span class="logo-mo">{{ $layout_info->logo_text ?? 'RootSkin' }}</span>
          @endif
        </a>
      </h1>

      <nav class="gnb-menu">
        <ul>
          @forelse($main_menu->list as $menu_item)
            <li class="gnb-item {{ !empty($menu_item['list']) ? 'has-submenu' : '' }}">
              <a href="{{ $menu_item['href'] }}" @if($menu_item['open_window'] === 'Y') target="_blank" @endif>{{ $menu_item['text'] }}</a>
              @if(!empty($menu_item['list']))
                <div class="submenu">
                  <div class="submenu-title">{{ $menu_item['text'] }}</div>
                  @foreach($menu_item['list'] as $child_item)
                    <a href="{{ $child_item['href'] }}" @if($menu_item['open_window'] === 'Y') target="_blank" @endif>{{ $child_item['text'] }}</a>
                  @endforeach
                </div>
              @endif
            </li>
          @empty
            <li class="gnb-item">No menu items available</li>
          @endforelse
        </ul>
      </nav>
    </div>

    <div class="header-right">
      @if ($layout_info->use_search !== 'N')
      <button class="search-toggle" id="searchToggleBtn">
        <img src="icons/search.svg" alt="search">
      </button>
      @endif

      @if(Context::get('is_logged') && $layout_info->use_notification !== 'N')
        @include('components/notification-dropdown')
      @endif

      <div class="user-actions">
        @if(Context::get('is_logged'))
          @include('components/user-profile-dropdown')
        @else
          <a href="@url(['act' => 'dispMemberLoginForm'])" class="user-link login">로그인</a>
          <a href="@url(['act' => 'dispMemberSignUpForm'])" class="btn-signup">회원가입</a>
        @endif
      </div>
    </div>
  </div>

  @if ($layout_info->use_search !== 'N')
  <!-- Search Box -->
  <form class="search-box" id="searchBox" action="{{ \RX_BASEURL }}" method="GET">
    <input type="hidden" name="mid" value="{{ $mid }}" />
    <input type="hidden" name="act" value="IS" />
    <div class="search-inner">
      <input type="text" name="is_keyword" value="{{ $is_keyword }}" placeholder="검색어를 입력해주세요 (최소 2자 이상)" class="search-input" required>
      <button type="submit" class="search-submit">
        <img src="icons/search.svg" alt="search">
      </button>
      <button type="button" class="search-close" id="searchCloseBtn">×</button>
    </div>
  </form>
  @endif
</header>

<!-- Mobile Menu -->
<nav class="mobile-menu" id="mobileMenu">
  <div class="mobile-menu-header">
    <h2>메뉴</h2>
    <button class="mobile-menu-close" id="mobileMenuCloseBtn">×</button>
  </div>

  <ul class="mobile-menu-list">
    @forelse($main_menu->list as $menu_item)
      <li class="mobile-menu-item {{ !empty($menu_item['list']) ? 'has-submenu' : '' }}">
        @if(!empty($menu_item['list']))
          <button class="mobile-menu-toggle" onclick="toggleMobileSubmenu(this)">
            <span>{{ $menu_item['text'] }}</span>
            <img src="icons/chevron-down.svg" alt="chevron" class="arrow-icon">
          </button>
          <ul class="mobile-submenu">
            @foreach($menu_item['list'] as $child_item)
              <li><a href="{{ $child_item['href'] }}" @if($menu_item['open_window'] === 'Y') target="_blank" @endif>{{ $child_item['text'] }}</a></li>
            @endforeach
          </ul>
        @else
          <a href="{{ $menu_item['href'] }}" @if($menu_item['open_window'] === 'Y') target="_blank" @endif>{{ $menu_item['text'] }}</a>
        @endif
      </li>
    @empty
      <li class="mobile-menu-item">No menu items available</li>
    @endforelse
  </ul>

  @if(!Context::get('is_logged'))
  <div class="mobile-menu-auth">
    <a href="@url(['act' => 'dispMemberLoginForm'])" class="mobile-login login">로그인</a>
    <a href="@url(['act' => 'dispMemberSignUpForm'])" class="mobile-signup">회원가입</a>
  </div>
  @endif
</nav>

<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<!-- Login Modal -->
@if(!Context::get('is_logged'))
  @include('components/login-modal')
@endif

<!-- Main Content Wrapper -->
<div class="content-wrapper">
  <div class="container">
    <div class="content-layout {{ $layout_info->use_aside !== 'Y' ? 'no-sidebar' : '' }}">
      <!-- Left Sidebar -->
      @if($layout_info->use_aside === 'Y')
      <aside class="sidebar-left">
        {!! $layout_info->aside_content ?? '사이드바 내용을 입력해주세요.' !!}
      </aside>
      @endif

      <!-- Main Content -->
      <main class="main-content">
        {!! $content !!}
      </main>
    </div>
  </div>
</div>

{!! Context::getHtmlFooter() !!}

<script>
  // Mobile Menu Toggle
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
  const mobileMenuCloseBtn = document.getElementById('mobileMenuCloseBtn');

  function openMobileMenu() {
    mobileMenu.classList.add('active');
    mobileMenuOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileMenu() {
    mobileMenu.classList.remove('active');
    mobileMenuOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  mobileMenuBtn?.addEventListener('click', openMobileMenu);
  mobileMenuCloseBtn?.addEventListener('click', closeMobileMenu);
  mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

  // Get all dropdown elements
  const searchToggleBtn = document.getElementById('searchToggleBtn');
  const searchBox = document.getElementById('searchBox');
  const searchCloseBtn = document.getElementById('searchCloseBtn');
  const notificationToggleBtn = document.getElementById('notificationToggleBtn');
  const notificationDropdown = document.getElementById('notificationDropdown');
  const userProfileBtn = document.getElementById('userProfileBtn');
  const userDropdown = document.getElementById('userDropdown');

  // Search Toggle
  searchToggleBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    const isSearchActive = searchBox.classList.contains('active');

    // Close notification dropdown if open
    if (notificationDropdown) {
      notificationDropdown.classList.remove('active');
    }
    // Close user profile dropdown if open
    if (userDropdown) {
      userDropdown.classList.remove('active');
    }

    // Toggle search box
    if (isSearchActive) {
      searchBox.classList.remove('active');
    } else {
      searchBox.classList.add('active');
    }
  });

  searchCloseBtn?.addEventListener('click', () => {
    searchBox.classList.remove('active');
  });

  // Close menu on resize
  window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) {
      closeMobileMenu();
    }
  });

  // Sticky Header
  let lastScroll = 0;
  const header = document.getElementById('header');

  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    lastScroll = currentScroll;
  });

  // Mobile Submenu Toggle
  function toggleMobileSubmenu(button) {
    const menuItem = button.closest('.mobile-menu-item');
    const submenu = menuItem.querySelector('.mobile-submenu');
    const isOpen = menuItem.classList.contains('open');

    // Close all other submenus
    document.querySelectorAll('.mobile-menu-item.open').forEach(item => {
      if (item !== menuItem) {
        item.classList.remove('open');
      }
    });

    // Toggle current submenu
    if (isOpen) {
      menuItem.classList.remove('open');
    } else {
      menuItem.classList.add('open');
    }
  }

  // User Profile Dropdown Toggle
  if (userProfileBtn && userDropdown) {
    userProfileBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isUserDropdownActive = userDropdown.classList.contains('active');

      // Close notification dropdown if open
      if (notificationDropdown) {
        notificationDropdown.classList.remove('active');
      }
      // Close search box if open
      if (searchBox) {
        searchBox.classList.remove('active');
      }

      // Toggle user dropdown
      if (isUserDropdownActive) {
        userDropdown.classList.remove('active');
      } else {
        userDropdown.classList.add('active');
      }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!userDropdown.contains(e.target) && !userProfileBtn.contains(e.target)) {
        userDropdown.classList.remove('active');
      }
    });

    // Prevent dropdown from closing when clicking inside
    userDropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });
  }

  // Notification Dropdown Toggle
  if (notificationToggleBtn && notificationDropdown) {
    notificationToggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isNotificationActive = notificationDropdown.classList.contains('active');

      // Close user profile dropdown if open
      if (userDropdown) {
        userDropdown.classList.remove('active');
      }
      // Close search box if open
      if (searchBox) {
        searchBox.classList.remove('active');
      }

      // Toggle notification dropdown
      if (isNotificationActive) {
        notificationDropdown.classList.remove('active');
      } else {
        notificationDropdown.classList.add('active');
      }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
      if (!notificationDropdown.contains(e.target) && !notificationToggleBtn.contains(e.target)) {
        notificationDropdown.classList.remove('active');
      }
    });

    // Prevent dropdown from closing when clicking inside
    notificationDropdown.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    // Tab switching
    const tabBtns = notificationDropdown.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();

        // Handle "모두 삭제" button (second tab)
        if (btn.dataset.tab === 'unread') {
          if (confirm('모든 알림을 삭제하시겠습니까?')) {
            // TODO: Implement delete all notifications functionality
            console.log('Delete all notifications');
          }
        } else {
          // Regular tab switching
          tabBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
        }
      });
    });
  }

  // Login Modal
  const loginModalOverlay = document.getElementById('loginModalOverlay');
  const loginModalCloseBtn = document.getElementById('loginModalCloseBtn');
  const loginLinks = document.querySelectorAll('a.login');

  function openLoginModal(e) {
    e.preventDefault();
    if (loginModalOverlay) {
      loginModalOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeLoginModal() {
    if (loginModalOverlay) {
      loginModalOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  // Add click event to all login links
  loginLinks.forEach(link => {
    link.addEventListener('click', openLoginModal);
  });

  // Close modal when clicking close button
  if (loginModalCloseBtn) {
    loginModalCloseBtn.addEventListener('click', closeLoginModal);
  }

  // Close modal when clicking overlay
  if (loginModalOverlay) {
    loginModalOverlay.addEventListener('click', (e) => {
      if (e.target === loginModalOverlay) {
        closeLoginModal();
      }
    });
  }

  // Close modal on ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && loginModalOverlay?.classList.contains('active')) {
      closeLoginModal();
    }
  });
</script>
