@php
  $logged_info = Context::get('logged_info');
  $grant = Context::get('grant');

  $has_profile_image = !empty($logged_info->profile_image?->src);
  $profile_image = $logged_info->profile_image?->src ?? '';
  $nick_name = $logged_info->nick_name ?? '사용자';

  // Point calculation
  if (!isset($GLOBALS['_point_config_cache'])) {
    $GLOBALS['_point_config_cache'] = getModel('module')->getModuleConfig('point');
  }
  $pointConfig = $GLOBALS['_point_config_cache'];

  $user_level = 0;
  $user_points = 0;
  $percent = 0;
  $nextstep = 0;
  $nextpoint = 0;

  if (isset($logged_info->member_srl) && isset($pointConfig->level_step)) {
    $point = (int)(getModel('point')->getPoint($logged_info->member_srl));
    $user_points = $point;

    // Calculate level efficiently
    foreach ($pointConfig->level_step as $idx => $threshold) {
      if ($point >= $threshold) $user_level = $idx;
      else break;
    }

    // Bounds check and calculate progression
    $maxLevel = count($pointConfig->level_step);
    if ($user_level >= $maxLevel) {
      $nextpoint = $pointConfig->level_step[$maxLevel];
      $percent = 100;
      $nextstep = 0;
    } else {
      $prepoint = $pointConfig->level_step[$user_level];
      $nextpoint = $pointConfig->level_step[$user_level + 1];
      $range = $nextpoint - $prepoint;

      $percent = $point < 0 ? 0 : min(100, (int)(($point - $prepoint) / $range * 100));
      $nextstep = max(0, $nextpoint - $point);
    }
  }

  $user_points_formatted = number_format($user_points);
  $progress_style = 'width: '.$percent.'%';
@endphp
@load('dropdowns.scss')

<div class="user-profile-wrapper">
  <button class="user-profile-btn" id="userProfileBtn">
    @if($has_profile_image)
      <img src="{{ $profile_image }}" alt="{{ $nick_name }}" class="profile-image">
    @else
      <img src="../icons/user.svg" alt="user">
    @endif
  </button>
  <div class="user-dropdown" id="userDropdown">
    <div class="dropdown-header">
      <div class="dropdown-user-info">
        <strong class="user-name">{{ $nick_name }}</strong>
        <div class="user-level">
          <span class="level-label">레벨 {{ $user_level }}</span>
          <span class="level-separator">/</span>
          <span class="points-label">포인트 {{ $user_points_formatted }}</span>
        </div>
      </div>
      <div class="progress-bar" title="포인트 {{ $user_points }}/{{ $nextpoint }} - {{ $percent }}%">
        <div class="progress-fill" style="{{ $progress_style }}"></div>
      </div>
      <div class="next-level-info">다음 레벨까지 {{ number_format($nextstep) }} 남음</div>
    </div>
    <ul class="dropdown-menu">
      @if($logged_info->is_admin == 'Y')
      <li>
        <a href="{{ getUrl('', 'module', 'admin') }}" target="_blank" class="dropdown-item">
          <img src="../icons/settings.svg" alt="settings">
          <span>{{ $lang->cmd_management }}</span>
        </a>
      </li>
      @endif
      @if($grant->manager ?? false)
      <li>
        <a href="{{ getUrl('','module','admin','act','dispLayoutAdminModify','layout_srl',$layout_info->layout_srl) }}" onclick="window.open(this.href);return false;" class="dropdown-item">
          <img src="../icons/grid.svg" alt="grid">
          <span>레이아웃 수정</span>
        </a>
      </li>
      <li>
        <a href="{{ getUrl('','module','admin','act','dispWidgetAdminDownloadedList') }}" onclick="window.open(this.href);return false;" class="dropdown-item">
          <img src="../icons/widget.svg" alt="widget">
          <span>위젯코드 생성</span>
        </a>
      </li>
      <li>
        <a href="{{ getUrl('','module','admin','act','dispMenuAdminSiteMap') }}" onclick="window.open(this.href);return false;" class="dropdown-item">
          <img src="../icons/menu.svg" alt="menu">
          <span>사이트 메뉴 편집</span>
        </a>
      </li>
      <li class="dropdown-divider"></li>
      @endif
      <li>
        <a href="{{ getUrl('', 'act', 'dispMemberInfo','member_srl','') }}" class="dropdown-item">
          <img src="../icons/user.svg" alt="user">
          <span>마이페이지</span>
        </a>
      </li>
      <li>
        <a href="{{ getUrl('', 'act', 'dispMemberScrappedDocument', 'page','') }}" class="dropdown-item">
          <img src="../icons/bookmark.svg" alt="bookmark">
          <span>스크랩</span>
        </a>
      </li>
      <li>
        <a href="{{ getUrl('', 'act', 'dispMemberOwnDocument', 'page','') }}" class="dropdown-item">
          <img src="../icons/clock.svg" alt="clock">
          <span>작성글</span>
        </a>
      </li>
      <li>
        <a href="{{ getUrl('', 'act', 'dispMemberOwnComment', 'page','') }}" class="dropdown-item">
          <img src="../icons/message.svg" alt="message">
          <span>작성댓글</span>
        </a>
      </li>
      <li class="dropdown-divider"></li>
      <li>
        <a href="{{ getUrl('', 'act', 'dispMemberLogout') }}" class="dropdown-item">
          <img src="../icons/logout.svg" alt="logout">
          <span>{{ $lang->cmd_logout }}</span>
        </a>
      </li>
    </ul>
  </div>
</div>
