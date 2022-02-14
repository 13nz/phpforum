  </body>
  <footer>
    <div class="container-fluid">
      <div class="row">
        <div class="col-3 offset-9 col-sm-3 offset-sm-9 col-md-2 offset-md-10 col-lg-2 offset-lg-10 my-1">

            <select name="theme" onchange="this.form.submit()">
              <option disabled>Theme</option>
              <option value="default" onclick="swapStyleSheet('css/default.css');">Default</option>
              <option value="basic" onclick="swapStyleSheet('css/basic.css');">Basic</option>
              <option value="sunset" onclick="swapStyleSheet('css/sunset.css');">Sunset</option>
              <option value="dust" onclick="swapStyleSheet('css/dust.css');">Dust</option>
              <option value="dark" onclick="swapStyleSheet('css/dark.css');">Dark</option>
              <option value="purple" onclick="swapStyleSheet('css/purple.css');">Purple</option>
            </select>

        </div>
      </div>
    </div>
  </footer>
</html>
