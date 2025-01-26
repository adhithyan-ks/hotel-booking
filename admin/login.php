<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles/styles.css" />
    <link rel="stylesheet" href="styles/registerstyle.css" />
    <link rel="icon" type="image/x-icon" href="/images/hotel-logo.png" />
    <title>Login</title>
  </head>

  <body>
    <main class="container" border>
      <!-- From Uiverse.io by Yaya12085 -->
      <!-- From Uiverse.io by kyle1dev -->
      <form class="modern-form">
        <div class="form-title">Login</div>

        <div class="form-body">
          <div class="input-group">
            <div class="input-wrapper">
              <i class="fa-solid fa-envelope input-icon"></i>
              <input
                required=""
                placeholder="Email"
                class="form-input"
                id="email"
                name="email"
                type="email"
              />
            </div>
          </div>

          <div class="input-group">
            <div class="input-wrapper">
              <i class="fa-solid fa-key input-icon"></i>
              <input
                required=""
                placeholder="Password"
                class="form-input"
                id="password"
                name="password"
                type="password"
              />
            </div>
          </div>
        </div>

        <button id="loginButton" class="submit-button" type="submit">
          <span class="button-text">Login</span>
          <div class="button-glow"></div>
        </button>

        <div class="form-footer">
          <a class="login-link" href="signup.html">
            Don't have an account? <span>Sign up</span>
          </a>
        </div>
      </form>
    </main>
  </body>
  <script
    src="https://kit.fontawesome.com/2e5e758ab7.js"
    crossorigin="anonymous"
  ></script>
</html>
