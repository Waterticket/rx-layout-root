@php
  $oNcenterliteModel = getModel('ncenterlite');
  $nCenterList = $oNcenterliteModel->getMyNotifyList();

  $messageList = executeQuery('communication.getNewMessage', [
    'receiver_srl' => $this->user->member_srl,
    'message_type' => 'R',
    'readed' => 'N',
    'list_count' => 5
  ], ['message_srl', 'sender_srl', 'title', 'nick_name', 'message.regdate']);

  $notiCount = $nCenterList->total_count + $messageList->page_navigation->total_count;

  if (
    $messageList->toBool() === false
    || is_array($messageList->data) === false
    || (is_array($messageList->data) === true && count($messageList->data) <= 0)
  ) {
    unset($messageList);
  }

  $has_notifications = $notiCount > 0;
  $notification_count = $notiCount;
@endphp
@load('dropdowns.scss')
@load('dropdowns.js')

<div class="notification-wrapper">
  <button class="notification-toggle" id="notificationToggleBtn">
    <img src="../icons/bell.svg" alt="notification">
    @if($notification_count > 0)
      <span class="notification-badge">{{ $notification_count > 99 ? '99+' : $notification_count }}</span>
    @endif
  </button>
  <div class="notification-dropdown" id="notificationDropdown">
    <div class="notification-header">
      <div class="notification-tabs">
        <button class="tab-btn active" data-tab="notification" onclick="changeDropdownItem(this)">
          알림
          @if ($nCenterList->total_count > 0)
            <span class="count">{{ $nCenterList->total_count }}</span>
          @endif
        </button>
        <button class="tab-btn" data-tab="message" onclick="changeDropdownItem(this)">
          쪽지
          @if (isset($messageList) && $messageList->page_navigation->total_count > 0)
            <span class="count">{{ $messageList->page_navigation->total_count }}</span>
          @endif
        </button>
      </div>
      <button class="delete-all-btn" onclick="onClickNotiReadAll()">모두 삭제</button>
    </div>

    <div class="notification-content">
      {{-- 알림 리스트 --}}
      <div id="notification-tab-notification" class="notification-tab-content active">
        @if ($nCenterList->total_count >= 1)
          <div class="notification-list">
            @foreach ($nCenterList->data as $item)
              <a href="{{ $item->url }}" class="notification-item">
                <div class="notification-avatar {{ $item->profileImage ? '' : 'default-avatar' }}">
                  @if ($item->profileImage)
                    <img src="{{ $item->profileImage }}" alt="">
                  @else
                    <img src="../icons/user.svg" alt="user">
                  @endif
                </div>
                <div class="notification-body">
                  <p class="notification-text">{!! $item->text !!}</p>
                  <span class="notification-date">{{ $item->ago }}</span>
                </div>
              </a>
            @endforeach
          </div>
          <div class="notification-footer">
            <a href="{getUrl('', 'mid', $mid, 'act', 'dispNcenterliteNotifyList')}" class="view-all-btn">전체 알림 보기</a>
          </div>
        @else
          <div class="notification-empty">
            <img src="../icons/bell.svg" alt="bell">
            <p class="empty-title">새로운 알림이 없습니다</p>
            <p class="empty-subtitle">알림을 받으면 여기에 표시됩니다</p>
          </div>
        @endif
      </div>

      {{-- 쪽지 리스트 --}}
      <div id="notification-tab-message" class="notification-tab-content">
        @if (isset($messageList) && count($messageList->data) >= 1)
          <div class="notification-list">
            @foreach ($messageList->data as $item)
              <a href="@url(['act' => 'dispCommunicationMessages', 'message_srl' => $item->message_srl])"
                 title="{{ strip_tags($item->title) }}"
                 class="notification-item">
                <div class="notification-avatar message-avatar">
                  <img src="../icons/mail.svg" alt="mail">
                </div>
                <div class="notification-body">
                  <p class="notification-text">{{ strip_tags($item->title) }}</p>
                  <span class="notification-date">{{ zdate($item->regdate, "Y-m-d") }}</span>
                </div>
              </a>
            @endforeach
          </div>
          <div class="notification-footer">
            <a href="@url(['mid' => $mid, 'act' => 'dispCommunicationMessages'])" class="view-all-btn">전체 쪽지 보기</a>
          </div>
        @else
          <div class="notification-empty">
            <img src="../icons/mail-empty.svg" alt="mail">
            <p class="empty-title">새로운 쪽지가 없습니다</p>
            <p class="empty-subtitle">쪽지함에서 지금까지 수신한<br/>쪽지를 모두 확인할 수 있습니다</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
