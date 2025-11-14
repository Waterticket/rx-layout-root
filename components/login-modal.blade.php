@php
  $validator_message = Context::get('XE_VALIDATOR_MESSAGE');
  $validator_id = Context::get('XE_VALIDATOR_ID');
  $validator_type = Context::get('XE_VALIDATOR_MESSAGE_TYPE');
  $current_url = htmlspecialchars(getRequestUriByServerEnviroment(), ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
@endphp
@load('login-modal.scss')

<!-- Login Modal -->
<div class="modal-overlay" id="loginModalOverlay">
  <div class="modal-container" id="loginModalContainer">
    <div class="modal-header">
      <h2 class="modal-title">{{ $lang->cmd_login }}</h2>
      <button class="modal-close" id="loginModalCloseBtn">
        <img src="../icons/close.svg" alt="close">
      </button>
    </div>

    <div class="modal-body">
      <form id="loginForm" action="{{ getUrl() }}" method="post" autocomplete="off">
        <input type="hidden" name="module" value="member" />
        <input type="hidden" name="act" value="procMemberLogin" />
        <input type="hidden" name="success_return_url" value="{{ $current_url }}" />
        <input type="hidden" name="error_return_url" value="{{ $current_url }}" />
        <input type="hidden" name="xe_validator_id" value="modules/member/skins" />
        <input type="hidden" name="device_token" value="" />

        @if($layout_info->login_option === 'id')
        <div class="form-group">
          <label for="user_id" class="form-label">아이디</label>
          <input
            type="text"
            id="user_id"
            name="user_id"
            class="form-input"
            placeholder="hongildong"
            required
            tabindex="1"
          >
        </div>
        @else
        <div class="form-group">
          <label for="user_id" class="form-label">이메일 주소</label>
          <input
            type="email"
            id="user_id"
            name="user_id"
            class="form-input"
            placeholder="user@email.com"
            required
            tabindex="1"
          >
        </div>
        @endif

        <div class="form-group">
          <label for="password" class="form-label">비밀번호</label>
          <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="•••••••••"
            required
            tabindex="2"
          >
        </div>

        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" name="keep_signed" id="keep_signed" value="Y" tabindex="3" data-lang="{{ $lang->about_keep_warning }}">
            <span>로그인 상태 유지</span>
          </label>
          <a href="{{ getUrl('', 'module', 'member', 'act','dispMemberFindAccount') }}" class="link-text" tabindex="5" rel="nofollow">
            {{ $lang->cmd_find_member_account }}
          </a>
        </div>

        @if($validator_message && $validator_id === 'modules/member/skins')
        <div class="validator-message error">
          <img src="../icons/alert-circle.svg" alt="alert">
          <span>{{ $validator_message }}</span>
        </div>
        @endif

        <button type="submit" class="btn-login" tabindex="4">로그인</button>

        <div class="form-footer">
          <span class="footer-text">아직 회원이 아니신가요?</span>
          <a href="{{ getUrl('', 'act','dispMemberSignUpForm') }}" class="link-signup" tabindex="6" rel="nofollow">회원가입 하기</a>
        </div>
      </form>
    </div>
  </div>
</div>

@if($validator_message && $validator_id === 'modules/member/skins')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const loginModalOverlay = document.getElementById('loginModalOverlay');
  if (loginModalOverlay) {
    loginModalOverlay.classList.add('active');
  }
});
</script>
@endif
