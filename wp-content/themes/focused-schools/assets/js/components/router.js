/* ==========================================================================
   Focused Schools — single-file router
   Used only by app.html (the one-file build). Every page lives in the DOM as
   a <div data-route>; this shows one at a time and keeps the header, footer,
   and deep links working. The multi-page build in site/*.html does not load
   this file.
   ========================================================================== */

(function () {
  "use strict";

  var ROUTES = {
    home: "Focused Schools — Every student. Every classroom. Every day.",
    about: "About — Focused Schools",
    team: "Team — Focused Schools",
    services: "Services — Focused Schools",
    impact: "Impact Stories — Focused Schools",
    story: "Impact Story — Focused Schools",
    podcast: "Podcast — Conversations on Learning | Focused Schools",
    contact: "Contact — Focused Schools"
  };

  // Maps the multi-page filenames onto routes so every existing href keeps working.
  var FILE_TO_ROUTE = {
    "index.html": "home",
    "about.html": "about",
    "team.html": "team",
    "services.html": "services",
    "impact-stories.html": "impact",
    "impact-story.html": "story",
    "podcast.html": "podcast",
    "contact.html": "contact"
  };

  function $(s, r) { return (r || document).querySelector(s); }
  function $$(s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); }

  function parseHash() {
    var h = (window.location.hash || "").replace(/^#\/?/, "");
    if (!h) return { route: "home", anchor: "", query: "" };
    var qi = h.indexOf("?");
    var query = qi > -1 ? h.slice(qi) : "";
    if (qi > -1) h = h.slice(0, qi);
    var parts = h.split("#");
    var route = parts[0] || "home";
    if (!ROUTES[route]) {
      // A bare anchor such as #stories or #roster: keep the current route.
      var cur = document.body.getAttribute("data-page") || "home";
      return { route: cur, anchor: route, query: query };
    }
    return { route: route, anchor: parts[1] || "", query: query };
  }

  var mounted = {};

  function show(route, anchor) {
    $$("[data-route]").forEach(function (el) {
      el.hidden = el.getAttribute("data-route") !== route;
    });
    document.body.setAttribute("data-page", route);
    document.title = ROUTES[route] || ROUTES.home;

    // Active nav state, rendered by site.js against the file hrefs.
    $$(".fs-nav__link, .fs-menu__list a").forEach(function (a) {
      var file = (a.getAttribute("href") || "").replace(/^#\//, "");
      var r = FILE_TO_ROUTE[file] || (ROUTES[file] ? file : null);
      if (r === route) a.setAttribute("aria-current", "page");
      else a.removeAttribute("aria-current");
    });

    // Per-route initialisation, once each — except the single story, which
    // re-renders because its content depends on the ?story= parameter.
    if (route === "impact" && !mounted.impact) { window.FS.initStories(); mounted.impact = true; }
    if (route === "team" && !mounted.team) { window.FS.initTeam(); mounted.team = true; }
    if (route === "podcast" && !mounted.podcast) { window.FS.initVideos(); mounted.podcast = true; }
    if (route === "services" && !mounted.services) { window.FS.initServices(); mounted.services = true; }
    if (route === "contact" && !mounted.contact) { window.FS.initContact(); mounted.contact = true; }
    if (route === "story") window.FS.initSingleStory();

    if (route === "about" && !mounted.about) {
      mountTeamTeaser();
      mounted.about = true;
    }

    window.FS.reveal();

    if (anchor) {
      var target = document.getElementById(anchor);
      if (target) {
        window.scrollTo(0, Math.max(0, target.getBoundingClientRect().top + window.scrollY - 106));
        return;
      }
    }
    window.scrollTo(0, 0);
  }

  /* The About page shows the first six team members as a teaser. It uses its
     own hook so it never collides with the Team page's roster grid. */
  function mountTeamTeaser() {
    var grid = $("[data-fs-team-teaser]");
    if (!grid) return;
    var more = $("[data-fs-team-teaser-more]");
    var shown = 6;
    function render() {
      grid.innerHTML = window.FS_DATA.team.slice(0, shown).map(window.FS.teamCard).join("");
      if (more) more.parentNode.hidden = shown >= window.FS_DATA.team.length;
      window.FS.reveal(grid);
    }
    if (more) more.addEventListener("click", function () { shown += 6; render(); });
    render();
  }

  /* Rewrite every in-site link to a hash route, including the ones site.js
     renders into the header and footer after this script runs. */
  function rewriteLinks(scope) {
    $$("a[href]", scope || document).forEach(function (a) {
      var href = a.getAttribute("href");
      if (!href || /^(https?:|mailto:|tel:|#)/.test(href)) return;
      var qi = href.indexOf("?");
      var file = qi > -1 ? href.slice(0, qi) : href;
      var query = qi > -1 ? href.slice(qi) : "";
      var hi = file.indexOf("#");
      var anchor = hi > -1 ? file.slice(hi) : "";
      if (hi > -1) file = file.slice(0, hi);
      var route = FILE_TO_ROUTE[file];
      if (!route) return;
      a.setAttribute("href", "#/" + route + query + anchor);
    });
  }

  function onRoute() {
    var r = parseHash();
    show(r.route, r.anchor);
  }

  function boot() {
    window.FS.mountChrome("home");
    rewriteLinks();
    window.FS.initShared();
    window.FS.initBioDialog();
    window.FS.initVideoFacades();
    window.FS.initVideoModal();
    window.FS.initPartnerMap();

    // site.js renders the chrome synchronously, but re-run after a tick in case
    // anything mounts late.
    setTimeout(function () { rewriteLinks(); onRoute(); }, 0);

    window.addEventListener("hashchange", onRoute);

    // In-page anchors inside the active route (e.g. Browse Stories → #stories)
    // should scroll rather than navigate.
    document.addEventListener("click", function (e) {
      var a = e.target.closest && e.target.closest("a[href^='#']");
      if (!a) return;
      var href = a.getAttribute("href");
      if (href === "#top") {
        e.preventDefault();
        window.scrollTo(0, 0);
        return;
      }
      if (/^#\//.test(href)) return; // route link — let hashchange handle it
      var id = href.slice(1);
      var target = document.getElementById(id);
      if (!target) return;
      e.preventDefault();
      window.scrollTo(0, Math.max(0, target.getBoundingClientRect().top + window.scrollY - 106));
    });

    onRoute();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
