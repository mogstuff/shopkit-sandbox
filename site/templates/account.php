<?php snippet('header') ?>

  <?php if ($page->slider()->isNotEmpty()) snippet('slider',['photos'=>$page->slider()]) ?>

  <h1 dir="auto"><?php echo $page->title()->html() ?></h1>

  <?php echo $page->text()->kirbytext()->bidi() ?>

  <?php if ($update_message) { ?>
    <div class="uk-alert uk-alert-warning">
      <p dir="auto"><?php echo $update_message ?></p>
    </div>
  <?php } ?>

  <?php if ($delete_message) { ?>
    <div class="uk-alert uk-alert-danger">
      <p dir="auto"><?php echo $delete_message ?></p>
    </div>
  <?php } ?>

  <form dir="auto" class="uk-form uk-form-stacked" method="post">
    <div class="uk-form-row">
      <label><?php echo l::get('username') ?></label>
      <input disabled class="uk-form-width-large" type="text" value="<?php echo $user->username() ?>">
      <p class="uk-form-help-block uk-text-muted uk-margin-remove"><?php echo l::get('username-no-change') ?></p>
    </div>
    <div class="uk-form-row">
      <label for="email"><?php echo l::get('email-address') ?></label>
      <input class="uk-form-width-large" type="text" id="email" name="email" value="<?php echo $user->email() ?>">
    </div>
    <div class="uk-form-row">
      <label for="password"><?php echo l::get('password') ?></label>
      <input class="uk-form-width-large" type="password" id="password" name="password" value="" aria-describedby="passwordHelp">
      <p class="uk-form-help-block uk-text-muted uk-margin-remove" id="passwordHelp"><?php echo l::get('password-help') ?></p>
    </div>
    <div class="uk-form-row">
      <div>
        <label for="fullname"><?php echo l::get('full-name') ?></label>
        <input class="uk-form-width-large" type="text" id="fullname" name="fullname" value="<?php echo $user->firstName() ?>">
      </div>
    </div>
    <div class="uk-form-row">
      <label for="country"><?php echo l::get('country') ?></label>
      <select class="uk-form-width-large" name="country" id="country" aria-describedby="countryHelp">
        <?php foreach ($countries as $c) { ?>
          <option <?php echo $user->country() === $c->slug() ? 'selected' : '' ?> value="<?php echo $c->slug() ?>"><?php echo $c->title() ?></option>
        <?php } ?>
      </select>
      <p class="uk-form-help-block uk-text-muted uk-margin-remove" id="countryHelp"><?php echo l::get('country-help') ?></p>
    </div>
    <div class="uk-form-row">
      <div>
        <label for="discountcode"><?php echo l::get('discount-code') ?></label>
        <input class="uk-form-width-large" type="text" id="discountcode" name="discountcode" value="<?php echo $user->discountcode() ?>" aria-describedby="discountCodeHelp">
      <p class="uk-form-help-block uk-text-muted uk-margin-remove" id="discountCodeHelp"><?php echo l::get('discount-code-help') ?></p>
      </div>
    </div>
    <div class="uk-form-row">
      <button class="uk-button uk-button-primary uk-button-large" type="submit" name="update">
        <?php echo l::get('update') ?>
      </button>
    </div>
  </form>

  <?php if (!$user->hasPanelAccess()) { ?>
    <h3 dir="auto"><?php echo l::get('delete-account') ?></h3>
    <form dir="auto" class="deleteAccount uk-form uk-panel uk-panel-box" method="post">
        <input type="checkbox" name="deleteConfirm" required>
        <label for="deleteConfirm"><?php echo l::get('delete-account-text') ?></label>
        <button class="uk-button uk-margin-top" type="submit" name="delete"><?php echo l::get('delete-account-verify') ?></button>
    </form>
  <?php } ?>

<?php snippet('footer') ?>
