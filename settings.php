<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

settings_fields('settings_group');
$host = rawurlencode($_SERVER['HTTP_HOST']);
$path = rawurlencode($_SERVER['REQUEST_URI']);
$link = 'https://getchipbot.com/?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$signup_link = 'https://getchipbot.com/try-chipbot/get-started?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$login_link = 'https://getchipbot.com/user/dashboard?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$help_link = 'https://getchipbot.com/help?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$chat_link = 'https://getchipbot.com/help?start-chat&utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$pricing_link = 'https://getchipbot.com/pricing?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$upgrade_link = 'https://getchipbot.com/user/upgrade?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;
$tutorials_link = 'https://getchipbot.com/user/dashboard/tutorials?utm_source=wordpress&utm_medium=plugin&utm_content=' . $host . $path;


$nonce = wp_verify_nonce($_POST['chipbot_settings_form'], 'chipbot_form_update');
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);
$adminPath = get_admin_url() . 'admin.php?page=getchipbot-com&saved=true';
$pluginPath = plugins_url('chipbot');
$js_snippet = get_option('chipbot_js_snippet');


$domainId = '';
$extraScript = '';

if ($js_snippet) {
  // parse out everything after !function
  // then run it on the browser
  $jsVariables = str_replace('<script>', '', substr($js_snippet, 0, strpos($js_snippet, '!function')));
  preg_match_all('/\'([^\']+)\'/', $jsVariables, $matches);
  $matchedQuotes = array_reverse($matches[0]);

  if ($matchedQuotes && $matchedQuotes[0]) {
    $domainId = str_replace("'", '', $matchedQuotes[0]);

    // ensures we show a video if one exists
    $extraScript = "
      <script>
        const dateNow = Date.now();
        fetch('https://getchipbot.com/edge/api/v2/dl/'+dateNow+'/{$domainId}/init/preview')
          .then(response => response.json())
          .then(res => {
            const video = res && res.data && res.data.videoExpList && res.data.videoExpList[0];
            
            if (video) {
              window.asyncChipBotApi = window.asyncChipBotApi || [];
              window.asyncChipBotApi.push(['showVideoIcon', video.id]);
            }
          })
          .catch(e => console.error(e));
      </script>
    ";
  }
}
?>


<div class="wrap chipbot-com">
  <div class="postbox banner-container">
    <div>
      <img
        style="margin-bottom: 10px;"
        src="<?php echo $pluginPath; ?>/cb-logo-2.svg"
        alt="GetChipBot.com"
      />

      <h3>ChipBot Plugin Integration</h3>

      <?php if (!$domainId) { ?>
        <p>
          To connect your ChipBot with our cloud service, you&apos;ll need to
          get the ChipBot Embed Code from your dashboard. If you don't have an account, it only
          takes a few moments to create one.
        </p>

        <p>
          <?php echo $domainId ?>
        </p>

        <a
          class="button button-primary button-large"
          href="<?php echo $signup_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Create a new account
        </a>

        <a
          class="button button-secondary button-large"
          href="<?php echo $login_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Login to existing account
        </a>
      <?php } ?>

      <?php if ($domainId) { ?>
        <p>
          ChipBot is successfully connected to your WordPress website. If you have a WP caching
          plugin, you may need to clear cache to see ChipBot successfully.
        </p>

        <p>
          <a
            class="button button-primary button-large"
            href="<?php echo $login_link; ?>"
            target="_blank"
            rel="noreferrer noopener nofollow"
          >
            Go to ChipBot Dashboard
          </a>
        </p>

        <h3>
          Video Tutorials
        </h3>

        <p>
          Want to learn how to use your ChipBot effectively? Check out our video gallery
          of tutorials. Learn topics like: adding TikTok-like video experiences, using AI to
          automatically create your Help Desk, and enabling AI live chat.
        </p>

        <a
          class="button button-primary button-large"
          href="<?php echo $tutorials_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Go to Video Tutorials
        </a>
        </p>

        <h3>
          More Features, Higher Limits, and Remove Branding
        </h3>

        <p>
          Increase the amount of unique hits, videos, articles, and conversation threads by being on
          a paid plan. Higher plans also have access to AI tools for content generation and the
          ability to remove "powered by ChipBot."
        </p>

        <a
          class="button button-primary button-large"
          href="<?php echo $upgrade_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          See Pricing
        </a>

        <h3>
          Customer Support
        </h3>

        <p>
          Having trouble loading ChipBot? Access our customer support help desk.
        </p>

        <a
          class="button button-primary button-large"
          href="<?php echo $help_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Help Desk
        </a>

        <a
          class="button button-primary button-large"
          href="<?php echo $chat_link; ?>"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Live Chat
        </a>

        <a
          class="button button-primary button-large"
          href="mailto:support@getchipbot.com"
          target="_blank"
          rel="noreferrer noopener nofollow"
        >
          Email Support
        </a>
      <?php } ?>
    </div>
    <div>
      <?php if (!$domainId) { ?>
        <h3>How to Activate ChipBot in WordPress (Tutorial)</h3>

        <div class="video-container">
          <iframe width="560" height="315" src="https://www.youtube.com/embed/Toa0yFV7FYQ"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
        </div>

      <?php } ?>

      <?php if ($domainId) { ?>
        <h3>ChipBot Live Preview</h3>

        <div class="preview-container">
          <iframe
            src="<?php echo htmlspecialchars('data:text/html,' . rawurlencode('<!doctype html><html><head></head><body>' . $js_snippet . $extraScript . '</body></html>')); ?>"
          ></iframe>
        </div>

      <?php } ?>
    </div>
  </div>

  <h3>ChipBot Embed Code</h3>

  <div class="postbox">
    <form
      method="post"
      action="options.php"
      style="padding: 0 15px 15px;"
      class=""
    >
      <?php
      settings_fields('settings_group');
      ?>

      <table class="form-table">
        <tbody>
          <tr>
            <td width="200">
              ChipBot Code Snippet
            </td>
            <td>
            <textarea
              class="install-snippet"
              name="chipbot_js_snippet"
              id="chipbot_js_snippet"
              type="text"
              class="regular-text"
              placeholder="Paste ChipBot code snippet here..."
            ><?php echo get_option('chipbot_js_snippet') ?></textarea>

              <input
                type="hidden"
                name="_wp_http_referer"
                value="<?php echo $adminPath ?>"
              >
              <?php if ($queries['saved']) { ?>
                <div class="notice notice-success" style="padding: 20px;">
                  Your ChipBot settings are now saved! Go check your homepage to see your ChipBot.
                  You may need to clear your WP cache plugin and/or CDN if you have one enabled.
                </div>
              <?php } ?>

              <?php
              if (isset($_POST['chipbot_settings_form']) && !$nonce) { ?>
                <div class="error">
                  <p>For some reason, WordPress didn't feel like saving your data. Please try
                    again.</p>
                </div>
              <?php } ?>

              <?php wp_nonce_field('chipbot_form_update', 'chipbot_settings_form'); ?>

              <input type="hidden" name="_wp_http_referer"
                value="<?php echo $adminPath ?>">


              <div class="chipbot-submit">
                <?php submit_button(); ?>
              </div>

            </td>
          </tr>
        </tbody>
      </table>

      <p>
        For support, you can reach us directly on
        <a href="https://getchipbot.com?utm_source=wordpress&utm_medium=support" target="_blank"
          rel="noreferrer noopener nofollow">https://getchipbot.com</a>
        or email
        <a href="mailto:support@getchipbot.com">support@getchipbot.com</a>.
      </p>
    </form>
  </div>
</div>
