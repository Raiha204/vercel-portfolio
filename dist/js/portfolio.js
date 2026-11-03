/*
 * Portfolio behaviour: entrance animation, scroll reveal, card tilt, copy buttons, screenshot gallery.
 * Everything here is progressive enhancement. The pages are complete without it, and every animation
 * is skipped when the visitor prefers reduced motion.
 */
(function () {
  "use strict";

  var doc = document;
  var mq = function (q) { return window.matchMedia ? window.matchMedia(q).matches : false; };
  var reduce = mq("(prefers-reduced-motion: reduce)");
  var finePointer = mq("(hover: hover) and (pointer: fine)");

  /* 1. Headline: each word rises out of a mask, one after another. */
  function splitHeadline() {
    var h = doc.querySelector("[data-split]");
    if (!h) { return; }
    if (!reduce) {
      var text = h.textContent.replace(/\s+/g, " ").trim();
      h.setAttribute("aria-label", text);
      h.textContent = "";
      var words = text.split(" ");
      words.forEach(function (word, i) {
        var mask = doc.createElement("span");
        mask.className = "w";
        mask.setAttribute("aria-hidden", "true");
        var inner = doc.createElement("span");
        inner.style.setProperty("--i", String(i));
        inner.textContent = word;
        mask.appendChild(inner);
        h.appendChild(mask);
        if (i < words.length - 1) { h.appendChild(doc.createTextNode(" ")); }
      });
    }
    h.classList.add("is-split");
  }

  /* 2. Cards, section headings and contact cards ease in as they scroll into view. */
  function initReveal() {
    var items = [].slice.call(doc.querySelectorAll(".reveal"));
    if (!items.length) { return; }
    if (reduce || !("IntersectionObserver" in window)) {
      items.forEach(function (el) { el.classList.add("in"); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      var n = 0;
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) { return; }
        var el = entry.target;
        if (!el.style.getPropertyValue("--d")) { el.style.setProperty("--d", String(Math.min(n++, 4))); }
        el.classList.add("in");
        io.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -5% 0px" });
    items.forEach(function (el) { io.observe(el); });
  }

  /* 3. A scanner beam sweeps over the portrait once on load, and again when it is hovered. */
  function initScan() {
    var frame = doc.querySelector(".portrait-frame");
    if (!frame || reduce) { return; }
    var run = function () { frame.classList.add("scan-run"); };
    frame.addEventListener("animationend", function (e) {
      if (e.animationName === "scan") { frame.classList.remove("scan-run"); }
    });
    window.setTimeout(run, 650);
    frame.addEventListener("pointerenter", run);
  }

  /* 4. A soft light in the hero follows the pointer. */
  function initSpotlight() {
    var hero = doc.querySelector(".hero");
    if (!hero || reduce || !finePointer) { return; }
    var tx = 0, ty = 0, cx = null, cy = 0, raf = 0;
    function frame() {
      cx += (tx - cx) * 0.12;
      cy += (ty - cy) * 0.12;
      hero.style.setProperty("--mx", cx.toFixed(1) + "px");
      hero.style.setProperty("--my", cy.toFixed(1) + "px");
      raf = (Math.abs(tx - cx) > 0.5 || Math.abs(ty - cy) > 0.5) ? requestAnimationFrame(frame) : 0;
    }
    hero.addEventListener("pointermove", function (e) {
      var r = hero.getBoundingClientRect();
      tx = e.clientX - r.left;
      ty = e.clientY - r.top;
      if (cx === null) { cx = r.width * 0.74; cy = r.height * 0.3; }
      if (!raf) { raf = requestAnimationFrame(frame); }
    });
  }

  /* 5. Cards tilt slightly toward the pointer and show a light sheen. */
  function initTilt() {
    if (reduce || !finePointer) { return; }
    [].slice.call(doc.querySelectorAll(".card")).forEach(function (card) {
      card.addEventListener("pointermove", function (e) {
        var r = card.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width;
        var y = (e.clientY - r.top) / r.height;
        card.style.setProperty("--ry", ((x - 0.5) * 6).toFixed(2) + "deg");
        card.style.setProperty("--rx", ((0.5 - y) * 4).toFixed(2) + "deg");
        card.style.setProperty("--mx", (x * 100).toFixed(1) + "%");
        card.style.setProperty("--my", (y * 100).toFixed(1) + "%");
      });
      card.addEventListener("pointerleave", function () {
        card.style.setProperty("--rx", "0deg");
        card.style.setProperty("--ry", "0deg");
      });
    });
  }

  /* 6. Copy buttons on the contact cards. */
  function copyText(text) {
    function fallback() {
      var ta = doc.createElement("textarea");
      ta.value = text;
      ta.setAttribute("readonly", "");
      ta.style.cssText = "position:fixed;opacity:0;top:0;left:0";
      doc.body.appendChild(ta);
      ta.select();
      var ok = false;
      try { ok = doc.execCommand("copy"); } catch (err) { ok = false; }
      doc.body.removeChild(ta);
      return ok;
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(text).then(function () { return true; }, function () { return fallback(); });
    }
    return Promise.resolve(fallback());
  }
  function initCopy() {
    var status = doc.getElementById("copy-status");
    doc.addEventListener("click", function (e) {
      var btn = e.target.closest("[data-copy]");
      if (!btn) { return; }
      var label = btn.querySelector("span");
      var original = label ? label.textContent : "";
      copyText(btn.getAttribute("data-copy")).then(function (ok) {
        if (label) { label.textContent = ok ? "Copied" : "Select to copy"; }
        btn.classList.toggle("is-copied", ok);
        if (status) {
          status.textContent = ok
            ? (btn.getAttribute("data-copy-label") || "Text") + " copied to the clipboard."
            : "Copying was blocked. Select the text to copy it.";
        }
        window.setTimeout(function () {
          if (label) { label.textContent = original; }
          btn.classList.remove("is-copied");
        }, 1800);
      });
    });
  }

  /* Viber and email open another app. If nothing took over (no app installed, or the browser blocked it),
     copy the number or address instead and say so, so the button is never a dead end. */
  function initAppLinks() {
    // Shows the "copied" note and copies the number or address (also used when the app cannot be opened at all).
    function copyInstead(link) {
      var card = link.closest(".contact-card");
      var copyBtn = card && card.querySelector("[data-copy]");
      var hint = card && card.querySelector("[data-app-hint]");
      if (copyBtn) { copyBtn.click(); }
      if (hint) { hint.hidden = false; window.setTimeout(function () { hint.hidden = true; }, 8000); }
    }
    var framed = false;
    try { framed = window.self !== window.top; } catch (err) { framed = true; }

    doc.addEventListener("click", function (e) {
      var link = e.target.closest("[data-app-link]");
      if (!link) { return; }

      // Inside an embedded preview, a mailto: or viber: link would replace the whole frame with an error page.
      // Open it in its own tab instead; if the embed does not allow that, copy the text so nothing is lost.
      if (framed) {
        e.preventDefault();
        var win = null;
        try { win = window.open(link.href, "_blank"); } catch (err) { win = null; }
        if (!win) { copyInstead(link); return; }
        window.setTimeout(function () { try { win.close(); } catch (err) { /* already closed */ } }, 600);
        return;
      }

      var left = false;
      var mark = function () { left = true; };
      window.addEventListener("blur", mark, { once: true });
      doc.addEventListener("visibilitychange", mark, { once: true });
      window.setTimeout(function () {
        window.removeEventListener("blur", mark);
        doc.removeEventListener("visibilitychange", mark);
        if (left || doc.hidden) { return; }
        copyInstead(link);
      }, 1400);
    });
  }
  /* 7. Screenshot thumbnails on the project page. */
  function initGallery() {
    var gallery = doc.querySelector("[data-gallery]");
    if (!gallery) { return; }
    var main = gallery.querySelector("[data-gallery-main]");
    var cap = gallery.querySelector("[data-gallery-caption]");
    // Tall images (phone screenshots) get a portrait frame, wide ones the normal frame.
    function fitFrame() {
      var frame = main && main.parentNode;
      if (frame && main.naturalWidth) { frame.classList.toggle("is-portrait", main.naturalHeight > main.naturalWidth); }
    }
    if (main) { main.addEventListener("load", fitFrame); if (main.complete) { fitFrame(); } }
    gallery.addEventListener("click", function (e) {
      var thumb = e.target.closest("[data-thumb]");
      if (!thumb || !main) { return; }
      main.src = thumb.getAttribute("data-src");
      main.alt = thumb.getAttribute("data-alt") || "";
      var caption = thumb.getAttribute("data-caption") || "";
      if (cap) { cap.textContent = caption; cap.hidden = !caption; }
      [].slice.call(gallery.querySelectorAll("[data-thumb]")).forEach(function (b) {
        b.setAttribute("aria-pressed", b === thumb ? "true" : "false");
      });
    });
  }

  /* 8. A slim menu appears after scrolling past the top, and the section you are reading is highlighted. */
  function initStickyBar() {
    var bar = doc.querySelector("[data-stickybar]");
    if (!bar) { return; }
    var links = [].slice.call(bar.querySelectorAll(".sb-links a"));
    var show = function () { bar.classList.toggle("is-on", (window.pageYOffset || doc.documentElement.scrollTop) > 380); };
    window.addEventListener("scroll", show, { passive: true });
    show();
    if (!("IntersectionObserver" in window)) { return; }
    var current = null;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) { if (entry.isIntersecting) { current = entry.target.id; } });
      links.forEach(function (a) {
        var on = a.getAttribute("href") === "#" + current;
        a.classList.toggle("is-active", on);
        if (on) { a.setAttribute("aria-current", "true"); } else { a.removeAttribute("aria-current"); }
      });
    }, { rootMargin: "-35% 0px -55% 0px" });
    links.forEach(function (a) {
      var target = doc.getElementById((a.getAttribute("href") || "").slice(1));
      if (target) { io.observe(target); }
    });
  }

  /* 9. Click a screenshot on the project page to see it large. Esc or a click closes it. */
  function initLightbox() {
    var gallery = doc.querySelector("[data-gallery]");
    var main = gallery && gallery.querySelector("[data-gallery-main]");
    if (!main || typeof HTMLDialogElement === "undefined") { return; }
    var dlg = doc.createElement("dialog");
    dlg.className = "lightbox";
    dlg.setAttribute("aria-label", "Screenshot, enlarged");
    var img = doc.createElement("img");
    img.alt = "";
    var close = doc.createElement("button");
    close.type = "button";
    close.className = "lightbox-close";
    close.setAttribute("aria-label", "Close enlarged screenshot");
    close.innerHTML = "&times;";
    dlg.appendChild(img);
    dlg.appendChild(close);
    doc.body.appendChild(dlg);
    var open = function () {
      img.src = main.currentSrc || main.src;
      img.alt = main.alt || "";
      if (typeof dlg.showModal === "function") { dlg.showModal(); }
    };
    var frame = main.parentNode;
    frame.classList.add("is-zoomable");
    var zoom = doc.createElement("button");
    zoom.type = "button";
    zoom.className = "zoom-btn";
    zoom.setAttribute("aria-label", "Enlarge screenshot");
    zoom.innerHTML = "<svg viewBox=\"0 0 24 24\" width=\"18\" height=\"18\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" aria-hidden=\"true\"><path d=\"M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7\"/></svg>";
    frame.appendChild(zoom);
    main.addEventListener("click", open);
    zoom.addEventListener("click", open);
    close.addEventListener("click", function () { dlg.close(); });
    dlg.addEventListener("click", function (e) { if (e.target === dlg || e.target === img) { dlg.close(); } });
  }

  splitHeadline();
  initReveal();
  initScan();
  initSpotlight();
  /* 10. Project cards: the screenshots slide to the next or previous picture.
         Arrows, dots, a click on the picture, a swipe, or the left/right arrow keys. */
  function initCardCarousels() {
    [].slice.call(doc.querySelectorAll("[data-card-carousel]")).forEach(function (box) {
      var slides = [].slice.call(box.querySelectorAll("[data-slide]"));
      var dots = [].slice.call(box.querySelectorAll("[data-dot]"));
      var live = box.querySelector("[data-live]");
      if (slides.length < 2) { return; }
      var cur = 0;
      var EASE = "cubic-bezier(.65,0,.25,1)";

      function cancelAnimations(el) {
        if (el.getAnimations) { el.getAnimations().forEach(function (a) { a.cancel(); }); }
      }
      function go(to, dir) {
        to = (to + slides.length) % slides.length;
        if (to === cur) { return; }
        dir = dir || (to > cur ? 1 : -1);
        var from = slides[cur], next = slides[to];
        cur = to;
        slides.forEach(function (s) { if (s !== from && s !== next) { cancelAnimations(s); s.classList.remove("is-active"); } });
        cancelAnimations(from); cancelAnimations(next);
        next.classList.add("is-active");
        if (reduce || !next.animate) {
          from.classList.remove("is-active");
        } else {
          next.animate([{ transform: "translateX(" + (dir * 100) + "%)" }, { transform: "translateX(0)" }], { duration: 520, easing: EASE });
          var out = from.animate([{ transform: "translateX(0)" }, { transform: "translateX(" + (-dir * 100) + "%)" }], { duration: 520, easing: EASE, fill: "forwards" });
          out.onfinish = function () { from.classList.remove("is-active"); out.cancel(); };
        }
        dots.forEach(function (d, i) {
          d.classList.toggle("is-on", i === cur);
          if (i === cur) { d.setAttribute("aria-current", "true"); } else { d.removeAttribute("aria-current"); }
        });
        if (live) { live.textContent = "Screenshot " + (cur + 1) + " of " + slides.length; }
      }

      // The other pictures are loaded as soon as someone shows interest, so the slide never shows an empty frame.
      var warm = function () {
        [].slice.call(box.querySelectorAll("img")).forEach(function (img) { img.loading = "eager"; });
      };
      box.addEventListener("pointerenter", warm, { once: true });
      box.addEventListener("focusin", warm, { once: true });
      box.addEventListener("touchstart", warm, { once: true, passive: true });

      var prev = box.querySelector("[data-prev]"), next = box.querySelector("[data-next]");
      if (prev) { prev.addEventListener("click", function () { go(cur - 1, -1); }); }
      if (next) { next.addEventListener("click", function () { go(cur + 1, 1); }); }
      dots.forEach(function (d, i) { d.addEventListener("click", function () { go(i, i > cur ? 1 : -1); }); });

      var startX = null;
      box.addEventListener("pointerdown", function (e) {
        if (e.target.closest("[data-nav]") || (e.pointerType === "mouse" && e.button !== 0)) { startX = null; return; }
        startX = e.clientX;
      });
      box.addEventListener("pointercancel", function () { startX = null; });
      box.addEventListener("pointerup", function (e) {
        if (startX === null) { return; }
        var dx = e.clientX - startX;
        startX = null;
        if (Math.abs(dx) > 40) { go(cur + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1); }
        else if (Math.abs(dx) < 6) { go(cur + 1, 1); }
      });
      box.addEventListener("keydown", function (e) {
        if (e.target !== box) { return; }
        if (e.key === "ArrowRight") { e.preventDefault(); go(cur + 1, 1); }
        if (e.key === "ArrowLeft") { e.preventDefault(); go(cur - 1, -1); }
      });
    });
  }

  /* 11. Header: "Hi, I'm Carl Adrian" is typed, erased, replaced by the typed role ("Web Developer"), erased again,
         and so on in a loop. A click on the headline (or the keyboard button) pauses and resumes it, it pauses while the
         tab is hidden, and with "reduce motion" it runs once and stops on the role. Without this script the role is shown. */
  function initTypewriter() {
    var title = doc.querySelector("[data-typewriter]");
    if (!title) { return; }
    var copy = title.closest(".hero-copy") || doc.body;
    var lines = [].slice.call(title.querySelectorAll("[data-tw-line]"));
    var intro = [];
    try { intro = JSON.parse(title.getAttribute("data-intro") || "[]"); } catch (e) { intro = []; }
    var finals = lines.map(function (el) { return el.textContent; });

    if (!lines.length || intro.length !== lines.length) {
      title.classList.add("tw-skip");
      copy.classList.add("tw-lede");
      return;
    }

    var loop = !reduce;
    // Pace (milliseconds): raise a number to slow that part down.
    var TYPE_MS = 110, ERASE_MS = 32, HOLD_GREETING_MS = 2200, HOLD_ROLE_MS = 3500;
    var caret = doc.createElement("span");
    caret.className = "tw-caret";
    caret.setAttribute("aria-hidden", "true");

    function show(i, text) { lines[i].textContent = text; lines[i].appendChild(caret); }
    function idle(on) { caret.classList.toggle("is-idle", on); }
    function noop() {}

    // The whole show is a list of [wait, action] steps that one timer walks through (so it can pause and resume).
    var steps = [];
    function add(wait, fn) { steps.push([wait, fn]); }
    function type(i, text, per) {
      add(0, function () { idle(false); });
      for (var k = 1; k <= text.length; k++) { (function (n) { add(per + Math.round(Math.random() * 45), function () { show(i, text.slice(0, n)); }); })(k); }
    }
    function erase(i, text, per) {
      add(0, function () { idle(false); });
      for (var k = text.length - 1; k >= 0; k--) { (function (n) { add(per, function () { show(i, text.slice(0, n)); }); })(k); }
    }
    function hold(ms) { add(0, function () { idle(true); }); add(ms, noop); }
    function eraseAll(texts) { for (var i = lines.length - 1; i >= 0; i--) { erase(i, texts[i], ERASE_MS); add(70, noop); } }

    var i, n;
    for (n = 0; n < lines.length; n++) { type(n, intro[n], n === 0 ? TYPE_MS : Math.round(TYPE_MS * 0.82)); add(200, noop); }   // Hi, I'm / Carl Adrian
    add(0, function () { copy.classList.add("tw-lede"); });        // the intro text and buttons arrive once the greeting is typed
    hold(HOLD_GREETING_MS);
    eraseAll(intro);
    add(0, function () { idle(true); });
    add(300, noop);
    for (n = 0; n < lines.length; n++) { type(n, finals[n], TYPE_MS); add(120, noop); }   // Web / Developer
    add(0, function () { title.classList.add("tw-done"); });
    var endOfFirstRun = steps.length;
    hold(HOLD_ROLE_MS);
    eraseAll(finals);
    add(0, function () { idle(true); });
    add(600, noop);

    var idx = 0, timer = 0, userPaused = false, tabHidden = false;
    function tick() {
      if (userPaused || tabHidden) { return; }
      if (!loop && idx >= endOfFirstRun) {                      // reduced motion: stop on the role
        timer = window.setTimeout(function () { caret.classList.add("is-off"); }, 2600);
        return;
      }
      var step = steps[idx];
      timer = window.setTimeout(function () {
        step[1]();
        idx = (idx + 1) % steps.length;
        tick();
      }, step[0]);
    }
    function pauseNow() { window.clearTimeout(timer); idle(true); }

    var toggle = null;
    function setPaused(on) {
      userPaused = on;
      title.setAttribute("title", on ? "Click to resume" : "Click to pause");
      if (toggle) { toggle.setAttribute("aria-pressed", on ? "true" : "false"); toggle.textContent = on ? "Resume typing animation" : "Pause typing animation"; }
      if (on) { pauseNow(); } else { tick(); }
    }
    if (loop) {
      title.setAttribute("title", "Click to pause");
      title.addEventListener("click", function () { setPaused(!userPaused); });
      toggle = doc.createElement("button");
      toggle.type = "button";
      toggle.className = "tw-toggle";
      toggle.setAttribute("aria-pressed", "false");
      toggle.textContent = "Pause typing animation";
      toggle.addEventListener("click", function () { setPaused(!userPaused); });
      title.parentNode.insertBefore(toggle, title.nextSibling);
      doc.addEventListener("visibilitychange", function () {
        tabHidden = doc.hidden;
        if (tabHidden) { pauseNow(); } else { tick(); }
      });
    }

    title.classList.add("tw-active");
    lines.forEach(function (el) { el.textContent = ""; });
    show(0, "");
    idle(true);
    timer = window.setTimeout(tick, 600);                         // the cursor blinks for a moment, then it starts
  }

  initTilt();
  initCopy();
  initAppLinks();
  initGallery();
  initStickyBar();
  initLightbox();
  initCardCarousels();
  initTypewriter();
})();
