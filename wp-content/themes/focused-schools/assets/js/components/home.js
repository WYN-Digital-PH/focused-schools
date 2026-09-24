/* ==========================================================================
   Focused Schools — Homepage behaviors
   Ambient hero video + console, sound modal, scroll-scrubbed emphasis rule
   and cycle diagram, testimonial carousel, contact form.
   Loaded after site.js.
   ========================================================================== */

(function () {
  "use strict";

  var VIDEO_ID = "kq4YCY5eOGI";
  var NOCOOKIE = "https://www.youtube-nocookie.com";
  var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function $(s, r) { return (r || document).querySelector(s); }
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }

  /* ----------------------------------------------- Ambient hero video --- */

  function initHero() {
    var frame = $("[data-hm-frame]");
    var slot = $("[data-hm-video]");
    var btn = $("[data-hm-play]");
    if (!frame || !slot) return;

    // The ambient loop is muted and decorative: never load it under
    // reduced-motion, and never let it take focus.
    if (!reduced) {
      var iframe = document.createElement("iframe");
      iframe.className = "hm-hero__video";
      iframe.title = "Muted Focused Schools background video";
      iframe.tabIndex = -1;
      iframe.setAttribute("aria-hidden", "true");
      iframe.setAttribute("allow", "autoplay; encrypted-media; picture-in-picture");
      iframe.setAttribute("referrerpolicy", "strict-origin-when-cross-origin");
      iframe.src = NOCOOKIE + "/embed/" + VIDEO_ID +
        "?autoplay=1&mute=1&controls=0&loop=1&playlist=" + VIDEO_ID +
        "&playsinline=1&modestbranding=1&rel=0&disablekb=1&enablejsapi=1";
      slot.appendChild(iframe);
    } else {
      frame.setAttribute("data-paused", "true");
      if (btn) btn.setAttribute("aria-pressed", "true");
    }

    if (btn) {
      btn.addEventListener("click", function () {
        var paused = frame.getAttribute("data-paused") === "true";
        var next = !paused;
        frame.setAttribute("data-paused", next ? "true" : "false");
        btn.setAttribute("aria-pressed", next ? "true" : "false");
        $("[data-hm-play-label]", btn).textContent = next ? "Play" : "Pause";
        var f = $("iframe", slot);
        if (f && f.contentWindow) {
          f.contentWindow.postMessage(JSON.stringify({
            event: "command",
            func: next ? "pauseVideo" : "playVideo",
            args: []
          }), NOCOOKIE);
        }
      });
    }
  }

  /* -------------------------------------------------- Watch with sound --- */

  function initVideoModal() {
    var modal = $("[data-hm-modal]");
    var open = $("[data-hm-open-video]");
    if (!modal || !open) return;
    var stage = $("[data-hm-modal-stage]", modal);
    var frame = $("[data-hm-frame]");
    var btn = $("[data-hm-play]");

    function show() {
      stage.innerHTML = '<iframe src="' + NOCOOKIE + "/embed/" + VIDEO_ID +
        '?autoplay=1&rel=0&modestbranding=1" title="Focused Schools video" ' +
        'allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>';
      modal.hidden = false;
      document.body.style.overflow = "hidden";
      // Pause the ambient loop so two soundtracks never compete.
      if (frame && frame.getAttribute("data-paused") !== "true" && btn) btn.click();
      var close = $("[data-hm-close-video]", modal);
      if (close) close.focus();
    }
    function hide() {
      modal.hidden = true;
      stage.innerHTML = "";
      document.body.style.overflow = "";
      open.focus();
    }

    open.addEventListener("click", show);
    $$("[data-hm-close-video]", modal).forEach(function (b) { b.addEventListener("click", hide); });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.hidden) hide();
    });
  }

  /* ----------------------------------------------------- Scroll scrubs --- */

  function initScrub() {
    var hold = $("[data-hm-hold]");
    var rule = $("[data-hm-rule]");
    var cycle = $("[data-hm-cycle]");
    var rotor = $("[data-hm-rotor]");
    var orbit = $("[data-hm-orbit]");
    var mark = $(".hm-cycle__mark");
    var rows = $$("[data-hm-step]");
    var raf = null;
    var current = -1;

    function setStep(i) {
      if (i === current) return;
      current = i;
      rows.forEach(function (r, n) {
        if (n === i) r.setAttribute("aria-current", "step");
        else r.removeAttribute("aria-current");
      });
    }

    function scrub() {
      raf = null;

      if (hold && rule && !reduced) {
        var hr = hold.getBoundingClientRect();
        var hspan = Math.max(1, hr.height - window.innerHeight);
        var hp = Math.min(1, Math.max(0, -hr.top / hspan));
        rule.style.width = (Math.min(1, hp * 2.2) * 100).toFixed(1) + "%";
      }

      if (cycle) {
        var cr = cycle.getBoundingClientRect();
        var cspan = Math.max(1, cr.height - window.innerHeight);
        var cp = Math.min(1, Math.max(0, -cr.top / cspan));
        if (!reduced) {
          var deg = cp * 360;
          if (rotor) rotor.style.transform = "rotate(" + deg.toFixed(2) + "deg)";
          if (orbit) {
            // Orbit radius is a CSS custom property so it tracks the mark size
            // at every breakpoint instead of being pinned to the desktop value.
            var r = mark
              ? (getComputedStyle(mark).getPropertyValue("--orbit-r") || "183px").trim()
              : "183px";
            orbit.style.transform = "rotate(" + deg.toFixed(2) + "deg) translateX(" + r + ")";
          }
        }
        setStep(Math.min(rows.length - 1, Math.floor(cp * rows.length)));
      }
    }

    function onScroll() {
      if (raf) return;
      raf = requestAnimationFrame(scrub);
    }

    // Hovering or focusing a phase row takes over from the scroll position.
    rows.forEach(function (r, i) {
      ["click", "mouseenter", "focus"].forEach(function (evt) {
        r.addEventListener(evt, function () { setStep(i); });
      });
    });

    window.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll, { passive: true });
    setStep(0);
    scrub();
  }

  /* ------------------------------------------------ Testimonial carousel --- */

  function initCarousel() {
    var track = $("[data-hm-track]");
    if (!track) return;
    var slides = $$(".hm-slide", track);
    var dots = $$("[data-hm-dot]");
    var counter = $("[data-hm-counter]");
    var i = 0;

    function go(n) {
      i = (n + slides.length) % slides.length;
      track.style.transform = "translateX(-" + i * 100 + "%)";
      slides.forEach(function (s, k) { s.setAttribute("data-active", k === i ? "true" : "false"); });
      dots.forEach(function (d, k) { d.setAttribute("aria-selected", k === i ? "true" : "false"); });
      if (counter) counter.textContent = (i + 1) + " / " + slides.length;
    }

    dots.forEach(function (d, k) { d.addEventListener("click", function () { go(k); }); });
    var prev = $("[data-hm-prev]");
    var next = $("[data-hm-next]");
    if (prev) prev.addEventListener("click", function () { go(i - 1); });
    if (next) next.addEventListener("click", function () { go(i + 1); });

    track.parentNode.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") { go(i - 1); }
      if (e.key === "ArrowRight") { go(i + 1); }
    });

    go(0);
  }

  /* ---------------------------------------------------------- Home form --- */

  function initForm() {
    var form = $("[data-hm-form]");
    if (!form) return;
    var status = $("[data-hm-status]");
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      // Production: POST to the form handler, then render this confirmation.
      if (status) status.textContent = "Thank you — we'll be in touch.";
      form.reset();
      if (window.dataLayer) window.dataLayer.push({ event: "home_form_success" });
    });
  }

  function boot() {
    initHero();
    initVideoModal();
    initScrub();
    initCarousel();
    initForm();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
