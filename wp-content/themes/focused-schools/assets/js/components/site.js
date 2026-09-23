/* ==========================================================================
   Focused Schools — site behaviors
   Vanilla ES5-safe JS, no dependencies. Every interaction is client-side.
   ========================================================================== */

(function () {
  "use strict";

  var D = window.FS_DATA;
  var LOGO = "https://focused-schools-rebrand.vercel.app/assets/logo/";
  var PAGE_SIZE = { stories: 6, team: 6, videos: 6 };

  /* ------------------------------------------------------------ helpers --- */

  function esc(s) {
    return String(s == null ? "" : s)
      .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;").replace(/'/g, "&#39;");
  }
  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
  function pad(n) { return (n < 10 ? "0" : "") + n; }
  function on(el, evt, fn) { if (el) el.addEventListener(evt, fn); }
  function param(name) {
    // Works for both real query strings and hash routes (#/route?story=slug),
    // so the single-file build and the multi-page build share one code path.
    var hash = window.location.hash || "";
    var qi = hash.indexOf("?");
    var src = qi > -1 ? hash.slice(qi) : window.location.search;
    var m = new RegExp("[?&]" + name + "=([^&#]*)").exec(src);
    return m ? decodeURIComponent(m[1].replace(/\+/g, " ")) : "";
  }
  function slugOf(s) { return s.slug; }

  /* ------------------------------------------------- header and footer --- */

  var NAV = [
    { key: "about", label: "About", href: "about.html" },
    { key: "team", label: "Team", href: "team.html" },
    { key: "services", label: "Services", href: "services.html" },
    { key: "impact", label: "Impact Stories", href: "impact-stories.html" },
    { key: "podcast", label: "Podcast", href: "podcast.html" },
    { key: "blog", label: "Blog", href: "blog.html" }
  ];

  function mountChrome(active) {
    var hdr = $("[data-fs-header]");
    var ftr = $("[data-fs-footer]");

    if (hdr) {
      hdr.className = "fs-hdr";
      hdr.innerHTML =
        '<div class="fs-shell">' +
          '<div class="fs-hdr__bar">' +
            '<a class="fs-hdr__logo" href="index.html" aria-label="Focused Schools — home">' +
              '<img src="' + LOGO + 'full-logo.svg" alt="Focused Schools" width="180" height="46" />' +
            '</a>' +
            '<nav class="fs-nav" aria-label="Primary navigation">' +
              NAV.map(function (n) {
                return '<a class="fs-nav__link" href="' + n.href + '"' +
                  (n.key === active ? ' aria-current="page"' : "") + '>' + esc(n.label) + '</a>';
              }).join("") +
            '</nav>' +
            '<a class="fs-btn fs-btn--primary fs-hdr__cta fs-btn--inline" href="contact.html">' +
              'Let\'s Talk <span aria-hidden="true">&rarr;</span></a>' +
            '<button class="fs-burger" type="button" aria-expanded="false" aria-controls="fs-menu" aria-label="Open menu">' +
              '<img src="' + LOGO + 'mark-1.svg" alt="" width="22" height="22" />' +
              '<span class="fs-burger__label">Menu</span>' +
              '<span class="fs-burger__lines" aria-hidden="true"><span></span><span></span></span>' +
            '</button>' +
            '<div class="fs-menu" id="fs-menu" data-open="false">' +
              '<div class="fs-menu__list">' +
                NAV.concat([{ key: "contact", label: "Contact", href: "contact.html" }]).map(function (n) {
                  return '<a href="' + n.href + '"' + (n.key === active ? ' aria-current="page"' : "") +
                    '>' + esc(n.label) + ' <span aria-hidden="true">&rarr;</span></a>';
                }).join("") +
              '</div>' +
              '<div class="fs-menu__foot">' +
                '<a class="fs-btn fs-btn--primary" href="contact.html">Let\'s Talk <span aria-hidden="true">&rarr;</span></a>' +
              '</div>' +
            '</div>' +
          '</div>' +
        '</div>';

      var burger = $(".fs-burger", hdr);
      var menu = $(".fs-menu", hdr);
      on(burger, "click", function () {
        var open = menu.getAttribute("data-open") === "true";
        menu.setAttribute("data-open", open ? "false" : "true");
        burger.setAttribute("aria-expanded", open ? "false" : "true");
        burger.setAttribute("aria-label", open ? "Open menu" : "Close menu");
      });
      on(document, "click", function (e) {
        if (!menu || menu.getAttribute("data-open") !== "true") return;
        if (hdr.contains(e.target)) return;
        menu.setAttribute("data-open", "false");
        burger.setAttribute("aria-expanded", "false");
      });
      on(document, "keydown", function (e) {
        if (e.key === "Escape" && menu && menu.getAttribute("data-open") === "true") {
          menu.setAttribute("data-open", "false");
          burger.setAttribute("aria-expanded", "false");
          burger.focus();
        }
      });
    }

    if (ftr) {
      ftr.className = "fs-ftr";
      ftr.innerHTML =
        '<img class="fs-ftr__mark" src="' + LOGO + 'mark-white.svg" alt="" aria-hidden="true" />' +
        '<div class="fs-shell fs-ftr__grid">' +
          '<div>' +
            '<img class="fs-ftr__logo" src="' + LOGO + 'full-logo-white.svg" alt="Focused Schools" width="216" height="55" />' +
            '<p class="fs-ftr__tag">Every student. Every classroom. Every day. No exceptions.</p>' +
            '<a class="fs-btn fs-btn--primary fs-btn--secondary-size" href="contact.html" style="height:46px;padding:0 26px;font-size:11px;letter-spacing:.1em">Contact Us <span aria-hidden="true">&rarr;</span></a>' +
          '</div>' +
          '<nav aria-label="Footer navigation">' +
            '<p class="fs-ftr__label">Explore</p>' +
            '<div class="fs-ftr__links">' +
              '<a href="about.html">About</a><a href="team.html">Team</a>' +
              '<a href="services.html">Services</a><a href="impact-stories.html">Impact Stories</a>' +
            '</div>' +
          '</nav>' +
          '<nav aria-label="Resources">' +
            '<p class="fs-ftr__label">Resources</p>' +
            '<div class="fs-ftr__links">' +
              '<a href="podcast.html">Podcast</a>' +
              '<a href="blog.html">Blog</a>' +
              '<a href="contact.html">Contact</a>' +
            '</div>' +
          '</nav>' +
          '<div>' +
            '<p class="fs-ftr__label">Connect</p>' +
            '<div class="fs-ftr__links" style="margin-bottom:32px">' +
              '<a href="mailto:' + D.email + '">' + D.email + '</a>' +
              '<a href="' + D.phoneHref + '">' + D.phone + '</a>' +
            '</div>' +
            '<div class="fs-social" aria-label="Social media">' +
              '<a href="https://twitter.com/focusedschools" aria-label="X">X</a>' +
              '<a href="https://www.facebook.com/focusedschools" aria-label="Facebook">f</a>' +
              '<a href="https://www.youtube.com/channel/UCg8W-jIlwxFCrVsU15gWFqA" aria-label="YouTube">&#9654;</a>' +
              '<a href="https://www.linkedin.com/company/focused-schools" aria-label="LinkedIn">in</a>' +
            '</div>' +
          '</div>' +
        '</div>' +
        '<div class="fs-ftr__bar"><div class="fs-shell">' +
          '<p>&copy;2026 Focused Schools. All Rights Reserved.</p>' +
          '<a href="#top">Back to top &uarr;</a>' +
        '</div></div>';
    }
  }

  /* ------------------------------------------------------------ reveals --- */

  var io = null;
  function reveal(scope) {
    var nodes = $$("[data-reveal]", scope || document).filter(function (n) { return !n.dataset.seen; });
    if (!nodes.length) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      nodes.forEach(function (n) { n.dataset.seen = "1"; });
      return;
    }
    if (!io) {
      io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (!e.isIntersecting) return;
          e.target.setAttribute("data-shown", "true");
          e.target.removeAttribute("data-pending");
          io.unobserve(e.target);
        });
      }, { threshold: 0.08, rootMargin: "0px 0px -6% 0px" });
    }
    nodes.forEach(function (n) {
      n.dataset.seen = "1";
      if (n.getBoundingClientRect().top < window.innerHeight * 0.95) return;
      n.setAttribute("data-pending", "true");
      io.observe(n);
    });
    // Safety: nothing may ever be stranded transparent.
    clearTimeout(reveal._t);
    reveal._t = setTimeout(function () {
      $$("[data-pending]").forEach(function (n) { n.removeAttribute("data-pending"); });
    }, 900);
  }

  /* -------------------------------------------------------- card markup --- */

  function mediaFallback(alt) {
    return '<img class="fs-media__mark" src="' + LOGO + 'mark-white.svg" alt="' + esc(alt || "") + '" />';
  }

  function storyCard(s) {
    var place = [s.district, s.state].filter(Boolean).join(" \u00B7 ");
    var legacy = s.source === "legacy";
    var href = "impact-story.html?story=" + encodeURIComponent(s.slug);
    return '' +
      '<article class="fs-card" data-reveal>' +
        '<div class="fs-media fs-media--16x9">' +
          (s.image
            ? '<img class="fs-media__photo" src="' + esc(s.image) + '" alt="Photograph from the ' + esc(s.district) + ' partnership." loading="lazy" width="1200" height="675" />'
            : mediaFallback("")) +
          (s.year ? '<span class="fs-media__badge">' + esc(s.year) + '</span>' : "") +
        '</div>' +
        '<div class="fs-card__body">' +
          '<p class="fs-place"><span class="fs-dot' + (legacy ? " fs-dot--legacy" : "") + '" aria-hidden="true"></span>' + esc(place) + '</p>' +
          '<h3 class="fs-h3 fs-clamp-3" style="margin-bottom:14px">' + esc(s.title) + '</h3>' +
          '<p class="fs-small fs-clamp-3" style="margin-bottom:22px">' + esc(s.excerpt) + '</p>' +
          '<div class="fs-card__foot">' +
            '<a class="fs-link" href="' + href + '" aria-label="Read story: ' + esc(s.title) + '">Read story <span class="fs-link__arrow" aria-hidden="true">&rarr;</span></a>' +
            '<span class="fs-pill' + (legacy ? " fs-pill--legacy" : "") + '">' + (legacy ? "Legacy" : "Story") + '</span>' +
          '</div>' +
        '</div>' +
      '</article>';
  }

  function teamCard(m, i) {
    return '' +
      '<article class="fs-card" data-reveal>' +
        '<div class="fs-media fs-media--4x5">' +
          (m.portrait
            ? '<img class="fs-media__photo" src="' + esc(m.portrait) + '" alt="Portrait of ' + esc(m.name) + ', ' + esc(m.role) + '" loading="lazy" width="900" height="1125" />'
            : mediaFallback("") + '<span class="fs-media__chip">Portrait pending</span>') +
        '</div>' +
        '<div class="fs-card__body">' +
          '<h3 class="fs-team__name fs-clamp-2">' + esc(m.name) + '</h3>' +
          '<p class="fs-team__role fs-clamp-3">' + esc(m.role) + '</p>' +
          (m.quote
            ? '<blockquote class="fs-quote-inline"><p class="fs-clamp-3">&ldquo;' + esc(m.quote) + '&rdquo;</p></blockquote>'
            : "") +
          ((m.bio || m.linkedin)
            ? '<div class="fs-card__foot">' +
                (m.bio
                  ? '<button class="fs-link" type="button" data-bio="' + i + '" aria-haspopup="dialog">Read bio <span class="fs-link__arrow" aria-hidden="true">&rarr;</span></button>'
                  : "<span></span>") +
                (m.linkedin
                  ? '<a class="fs-icon-tile" href="' + esc(m.linkedin) + '" target="_blank" rel="noopener" aria-label="' + esc(m.name) + ' on LinkedIn">in</a>'
                  : "") +
              '</div>'
            : "") +
        '</div>' +
      '</article>';
  }

  function videoCard(v) {
    var watchUrl = v.youtubeId
      ? "https://www.youtube.com/watch?v=" + encodeURIComponent(v.youtubeId)
      : "https://www.youtube.com/channel/UCg8W-jIlwxFCrVsU15gWFqA";
    return '' +
      '<article class="fs-card" data-reveal data-video="' + esc(v.youtubeId) + '">' +
        '<div class="fs-video__shell" data-video-shell>' +
          (v.thumbnail
            ? '<img class="fs-media__photo" src="' + esc(v.thumbnail) + '" alt="Video thumbnail: ' + esc(v.title) + '" loading="lazy" width="1280" height="720" />'
            : '<div class="fs-media fs-media--16x9" style="position:absolute;inset:0">' + mediaFallback("") + '</div>') +
          '<a class="fs-video__facade" href="' + watchUrl + '" data-video-play aria-label="Watch: ' + esc(v.title) + '">' +
            '<span class="fs-video__btn" aria-hidden="true">&#9654;</span>' +
          '</a>' +
          '<span class="fs-video__dim" aria-hidden="true"></span>' +
          (v.duration ? '<span class="fs-video__dur" aria-hidden="true">' + esc(v.duration) + '</span>' : "") +
        '</div>' +
        '<div class="fs-card__body">' +
          '<p class="fs-eyebrow fs-eyebrow--rasp" style="font-size:11px;letter-spacing:.12em;margin-bottom:10px">' +
            (v.n ? "Episode " + esc(v.n) : "Video") + '</p>' +
          '<h3 class="fs-video__title fs-clamp-3">' + esc(v.title) + '</h3>' +
          '<p class="fs-video__date">' + esc(v.date) + '</p>' +
          '<div class="fs-card__foot">' +
            '<a class="fs-link fs-link--rasp" href="' + watchUrl + '" data-video-play aria-label="Watch: ' + esc(v.title) + '">Watch <span class="fs-link__arrow" aria-hidden="true">&rarr;</span></a>' +
            '<a class="fs-icon-tile fs-icon-tile--rasp" href="' + watchUrl + '" target="_blank" rel="noopener" aria-label="Open &ldquo;' + esc(v.title) + '&rdquo; on YouTube">&#9654;</a>' +
          '</div>' +
        '</div>' +
      '</article>';
  }

  /* ---------------------------------------------- generic paged grid --- */

  function pagedGrid(opts) {
    var grid = $(opts.grid);
    if (!grid) return null;
    var btn = $(opts.more);
    var count = $(opts.count);
    var empty = $(opts.empty);
    var shown = opts.size;
    var items = opts.items;

    function render() {
      var slice = items.slice(0, Math.min(shown, items.length));
      grid.innerHTML = slice.map(opts.card).join("");
      grid.style.display = items.length ? "" : "none";
      if (empty) empty.hidden = items.length > 0;
      if (count) count.textContent = "Showing " + slice.length + " of " + items.length;
      if (btn) btn.parentNode.hidden = shown >= items.length || !items.length;
      reveal(grid);
      if (opts.after) opts.after(grid);
    }

    on(btn, "click", function () {
      shown += opts.size;
      render();
      var focusables = $$("a,button", grid);
      if (btn.parentNode.hidden && focusables.length) btn.blur();
    });

    return {
      render: render,
      set: function (next) { items = next; shown = opts.size; render(); }
    };
  }

  /* ----------------------------------------------------- Impact stories --- */

  function initStories() {
    var all = D.stories;
    var filters = { state: param("state") || "All", year: param("year") || "All" };

    var states = ["All"].concat(all.map(function (s) { return s.stateFull; })
      .filter(function (v, i, a) { return v && a.indexOf(v) === i; }));
    var years = ["All"].concat(all.map(function (s) { return s.year; })
      .filter(function (v, i, a) { return v && a.indexOf(v) === i; }));

    var bar = $("[data-fs-filters]");
    if (bar) {
      if (all.length < 4) {
        bar.hidden = true;
      } else {
        bar.innerHTML = [
          { key: "state", label: "State", aria: "Filter stories by state", options: states },
          { key: "year", label: "Year", aria: "Filter stories by year", options: years }
        ].map(function (g) {
          return '<div class="fs-filters__group">' +
            '<p class="fs-filters__label">' + g.label + '</p>' +
            '<div class="fs-filters__chips" role="group" aria-label="' + g.aria + '">' +
              g.options.map(function (o) {
                return '<button class="fs-chip" type="button" data-filter="' + g.key + '" data-value="' + esc(o) + '" aria-pressed="' +
                  (filters[g.key] === o ? "true" : "false") + '">' + esc(o) + '</button>';
              }).join("") +
            '</div></div>';
        }).join("");
      }
    }

    function matches(s) {
      if (filters.state !== "All" && s.stateFull !== filters.state) return false;
      if (filters.year !== "All" && s.year !== filters.year) return false;
      return true;
    }

    var paged = pagedGrid({
      grid: "[data-fs-story-grid]",
      more: "[data-fs-story-more]",
      count: "[data-fs-story-count]",
      empty: "[data-fs-story-empty]",
      size: PAGE_SIZE.stories,
      items: all.filter(matches),
      card: storyCard
    });
    if (!paged) return;

    function syncUrl() {
      var q = [];
      if (filters.state !== "All") q.push("state=" + encodeURIComponent(filters.state));
      if (filters.year !== "All") q.push("year=" + encodeURIComponent(filters.year));
      var url = window.location.pathname + (q.length ? "?" + q.join("&") : "");
      window.history.replaceState({}, "", url);
    }

    function apply() {
      $$("[data-filter]").forEach(function (b) {
        b.setAttribute("aria-pressed", filters[b.dataset.filter] === b.dataset.value ? "true" : "false");
      });
      paged.set(all.filter(matches));
      syncUrl();
    }

    on(bar, "click", function (e) {
      var b = e.target.closest("[data-filter]");
      if (!b) return;
      filters[b.dataset.filter] = b.dataset.value;
      apply();
    });
    on($("[data-fs-clear]"), "click", function () {
      filters.state = "All";
      filters.year = "All";
      apply();
    });

    paged.render();
    apply();

    // Featured spotlight — newest flagged record; section omitted when none.
    var spot = $("[data-fs-spotlight]");
    if (spot) {
      var feat = all.filter(function (s) { return s.featured && s.source !== "legacy"; })[0];
      if (!feat) {
        var sec = spot.closest("section");
        if (sec) sec.hidden = true;
      } else {
        spot.innerHTML =
          '<div class="fs-spotlight__copy">' +
            '<div class="fs-metas">' +
              [feat.district, feat.stateFull, feat.year].filter(Boolean).map(function (m) {
                return '<span class="fs-meta">' + esc(m) + '</span>';
              }).join("") +
            '</div>' +
            '<h2>' + esc(feat.title) + '</h2>' +
            '<p class="fs-body">' + esc(feat.excerpt) + '</p>' +
            '<div><a class="fs-btn fs-btn--white" href="impact-story.html?story=' + encodeURIComponent(feat.slug) + '">' +
              'Read the Story <span aria-hidden="true">&rarr;</span></a></div>' +
          '</div>' +
          '<div class="fs-spotlight__media">' +
            (feat.image ? '<img src="' + esc(feat.image) + '" alt="Photograph from the ' + esc(feat.district) + ' partnership." width="1200" height="1500" />' : "") +
          '</div>';
      }
    }
  }

  /* ---------------------------------------------- Single story template --- */

  var STORY_BODY = [
    { type: "h", text: "The district had three plans and no shared direction." },
    { type: "p", text: "When Champaign Unit 4 first called, there were three improvement plans in circulation — one from the district office, one from the previous superintendent's cabinet, and one written for a grant. All three were reasonable. None of them told a principal what to do on a Tuesday in February." },
    { type: "p", text: "We started where we always start: listening. Over eleven weeks we ran focus groups with teachers, principals, families, and students, and we read everything the district had already written. The goal was not a new plan. It was to find the plan that was already half-written in what people told us they cared about." },
    { type: "q", text: "I appreciate the data-driven approach that we took to arrive at this plan — and the engagement that occurred with the community.", cite: "School Committee Member, Champaign Unit 4" },
    { type: "h", text: "One plan, three priorities, named by everyone." },
    { type: "p", text: "The plan that came out of that process has three priorities and a five-year horizon. What matters more is what came with it: a quarterly progress rhythm for the School Committee, an operationalized version for cabinet and directors, and a one-page version every principal keeps in front of them." },
    { type: "img", text: "Champaign Unit 4 cabinet members reviewing quarterly progress against the plan.", cite: "Quarterly progress reviews replaced the old habit of reporting activity instead of outcomes." },
    { type: "p", text: "Two years in, the district runs the rhythm without us. That is the outcome we design for in every Strategy and Vision partnership — not a document, but a district that can steer." }
  ];

  var STORY_RESULTS = [
    { value: "3", unit: "Priorities", label: "One plan replacing three competing ones", aria: "Three priorities in one plan" },
    { value: "400+", unit: "Voices", label: "Community members engaged in the process", aria: "More than 400 community members engaged" },
    { value: "5", unit: "Year horizon", label: "Adopted unanimously by the School Committee", aria: "Five year horizon, adopted unanimously" }
  ];

  function initSingleStory() {
    var slug = param("story");
    var story = D.stories.filter(function (s) { return s.slug === slug; })[0] || D.stories[0];
    var isLead = story.slug === "champaign-unit-4";

    document.title = story.title + " — Impact Stories | Focused Schools";

    var crumb = $("[data-fs-crumb]");
    if (crumb) crumb.textContent = story.district;

    var media = $("[data-fs-story-media]");
    if (media) {
      media.innerHTML = story.image
        ? '<img src="' + esc(story.image) + '" alt="Photograph from the ' + esc(story.district) + ' partnership." width="2400" height="1030" fetchpriority="high" />'
        : "";
    }

    var metas = $("[data-fs-story-meta]");
    if (metas) {
      metas.innerHTML = [story.district, story.stateFull, story.year].filter(Boolean).map(function (m) {
        return '<span class="fs-meta">' + esc(m) + '</span>';
      }).join("");
    }
    var h1 = $("[data-fs-story-title]");
    if (h1) h1.textContent = story.title;
    var lede = $("[data-fs-story-excerpt]");
    if (lede) lede.textContent = story.excerpt;

    // At a glance — omitted entirely when a story carries no numbers.
    var results = isLead ? STORY_RESULTS : [];
    var glance = $("[data-fs-glance]");
    if (glance) {
      if (!results.length) {
        glance.closest("section").hidden = true;
      } else {
        glance.innerHTML = results.map(function (r) {
          return '<article class="fs-stat" data-reveal>' +
            '<p class="fs-stat__fig" aria-label="' + esc(r.aria) + '">' +
              '<span class="fs-stat__value fs-stat__value--sm">' + esc(r.value) + '</span>' +
              '<strong class="fs-stat__unit">' + esc(r.unit) + '</strong>' +
            '</p>' +
            '<h2 class="fs-stat__label">' + esc(r.label) + '</h2>' +
          '</article>';
        }).join("");
      }
    }

    // Detail rail — only the rows that exist; drops below two.
    var rows = [
      ["District", story.district],
      ["State", story.stateFull],
      ["Year", story.year],
      ["Service lane", isLead ? "Strategy and Vision" : ""],
      ["Partnership", isLead ? "Ongoing, 2 years" : ""]
    ].filter(function (r) { return r[1]; });

    var aside = $("[data-fs-story-aside]");
    var dl = $("[data-fs-story-details]");
    if (dl) {
      if (rows.length < 2 && aside) {
        aside.hidden = true;
        var body = $(".fs-story-body");
        if (body) body.style.gridTemplateColumns = "minmax(0,1fr)";
        $$(".fs-prose p").forEach(function (p) { p.style.maxWidth = "72ch"; });
      } else {
        dl.innerHTML = rows.map(function (r) {
          return '<div><dt>' + esc(r[0]) + '</dt><dd>' + esc(r[1]) + '</dd></div>';
        }).join("");
      }
    }

    var prose = $("[data-fs-story-body]");
    if (prose) {
      prose.innerHTML = STORY_BODY.map(function (b) {
        if (b.type === "h") return '<h2 class="fs-h2">' + esc(b.text) + '</h2>';
        if (b.type === "p") return '<p>' + esc(b.text) + '</p>';
        if (b.type === "q") {
          return '<blockquote class="fs-quote"><span class="fs-quote__marks" aria-hidden="true"><span></span><span></span></span>' +
            '<div><p class="fs-quote__text">' + esc(b.text) + '</p>' +
            '<p class="fs-quote__cite"><span aria-hidden="true"></span>' + esc(b.cite) + '</p></div></blockquote>';
        }
        return '<figure><img class="fs-photo fs-photo--16x9" src="' + D.IMG + 'retreat-3.jpg" alt="' + esc(b.text) + '" loading="lazy" width="1600" height="900" />' +
          '<figcaption>' + esc(b.cite) + '</figcaption></figure>';
      }).join("");
    }

    // Related — state, then service lane, then recency. Current story excluded.
    var related = $("[data-fs-related]");
    if (related) {
      var pool = D.stories.filter(function (s) { return s.slug !== story.slug; });
      var sameState = pool.filter(function (s) { return s.stateFull && s.stateFull === story.stateFull; });
      var rest = pool.filter(function (s) { return sameState.indexOf(s) === -1; });
      var picks = sameState.concat(rest).slice(0, 3);
      if (!picks.length) {
        related.closest("section").hidden = true;
      } else {
        related.innerHTML = picks.map(storyCard).join("");
      }
    }
    reveal();
  }

  /* --------------------------------------------------------------- Team --- */

  function initTeam() {
    var paged = pagedGrid({
      grid: "[data-fs-team-grid]",
      more: "[data-fs-team-more]",
      count: "[data-fs-team-count]",
      empty: "[data-fs-team-empty]",
      size: PAGE_SIZE.team,
      items: D.team,
      card: teamCard
    });
    if (paged) paged.render();
  }

  /* Bio dialog — mounted wherever team cards appear (Team page, About teaser). */
  function initBioDialog() {
    var dlg = $("[data-fs-bio]");
    if (!dlg) return;
    var trigger = null;

    function open(i) {
      var m = D.team[i];
      if (!m) return;
      $("[data-fs-bio-name]", dlg).textContent = m.name;
      $("[data-fs-bio-role]", dlg).textContent = m.role;
      var q = $("[data-fs-bio-quote]", dlg);
      q.hidden = !m.quote;
      if (m.quote) $("p", q).textContent = "\u201C" + m.quote + "\u201D";
      $("[data-fs-bio-text]", dlg).textContent = m.bio;
      var li = $("[data-fs-bio-linkedin]", dlg);
      li.hidden = !m.linkedin;
      if (m.linkedin) li.href = m.linkedin;
      dlg.hidden = false;
      document.body.style.overflow = "hidden";
      $("[data-fs-bio-name]", dlg).setAttribute("tabindex", "-1");
      $("[data-fs-bio-name]", dlg).focus();
    }
    function close() {
      dlg.hidden = true;
      document.body.style.overflow = "";
      if (trigger) trigger.focus();
    }

    on(document, "click", function (e) {
      var b = e.target.closest("[data-bio]");
      if (b) { trigger = b; open(parseInt(b.dataset.bio, 10)); return; }
      if (e.target.closest("[data-fs-bio-close]")) close();
    });
    on(document, "keydown", function (e) {
      if (e.key === "Escape" && !dlg.hidden) close();
      if (e.key === "Tab" && !dlg.hidden) {
        var f = $$('a[href],button:not([disabled])', dlg).filter(function (n) { return n.offsetParent !== null; });
        if (!f.length) return;
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
  }

  /* ================================================================ Blog === */

  function postCard(p) {
    return '<article class="fs-card fs-post" data-reveal>' +
      '<a class="fs-post__media" href="blog-post.html?post=' + encodeURIComponent(p.slug) + '" tabindex="-1" aria-hidden="true">' +
        '<img src="' + esc(p.image) + '" alt="" loading="lazy" width="1200" height="800" />' +
      '</a>' +
      '<div class="fs-card__body">' +
        '<p class="fs-post__tags">' +
          '<span class="fs-badge">' + esc(p.category) + '</span>' +
          '<span class="fs-post__meta"><time datetime="' + esc(p.date) + '">' + esc(p.dateLabel) + '</time>' +
            '<span aria-hidden="true">&middot;</span>' + p.read + ' min read</span>' +
        '</p>' +
        '<h3 class="fs-post__title"><a href="blog-post.html?post=' + encodeURIComponent(p.slug) + '">' + esc(p.title) + '</a></h3>' +
        '<p class="fs-post__excerpt">' + esc(p.excerpt) + '</p>' +
        '<p class="fs-card__foot"><span class="fs-link">Read More <span class="fs-link__arrow" aria-hidden="true">&raquo;</span></span></p>' +
      '</div>' +
    '</article>';
  }

  function initBlog() {
    var grid = $("[data-fs-post-grid]");
    if (!grid) return;

    var PER = 6;
    // The single-post breadcrumb links back as blog.html?cat=<Category>.
    var incoming = param("cat");
    var state = {
      q: "",
      cat: D.blogCategories.indexOf(incoming) > -1 ? incoming : "All",
      page: 1
    };

    var pillHost = $("[data-fs-post-filters]");
    var searchInput = $("[data-fs-post-search]");
    var countEl = $("[data-fs-post-count]");
    var emptyEl = $("[data-fs-post-empty]");
    var pagerEl = $("[data-fs-post-pager]");
    var featureEl = $("[data-fs-post-feature]");

    if (pillHost) {
      pillHost.innerHTML = ["All"].concat(D.blogCategories).map(function (c) {
        var active = c === state.cat;
        return '<button class="fs-chip" type="button" role="tab" aria-selected="' + active +
          '" aria-pressed="' + active + '" data-cat="' + esc(c) + '">' + esc(c) + '</button>';
      }).join("");
    }

    var featured = D.posts.filter(function (p) { return p.featured; })[0] || D.posts[0];
    if (featureEl) {
      featureEl.innerHTML =
        '<a class="fs-feature__media" href="blog-post.html?post=' + encodeURIComponent(featured.slug) + '" tabindex="-1" aria-hidden="true">' +
          '<img src="' + esc(featured.image) + '" alt="" width="1200" height="900" fetchpriority="high" />' +
        '</a>' +
        '<div class="fs-feature__copy">' +
          '<p class="fs-post__tags">' +
            '<span class="fs-badge fs-badge--solid">Featured</span>' +
            '<span class="fs-badge">' + esc(featured.category) + '</span>' +
            '<span class="fs-post__meta"><time datetime="' + esc(featured.date) + '">' + esc(featured.dateLabel) + '</time>' +
              '<span aria-hidden="true">&middot;</span>' + featured.read + ' min read</span>' +
          '</p>' +
          '<h3 class="fs-feature__title">' + esc(featured.title) + '</h3>' +
          '<p class="fs-feature__excerpt">' + esc(featured.excerpt) + '</p>' +
          '<a class="fs-btn fs-btn--primary" href="blog-post.html?post=' + encodeURIComponent(featured.slug) + '">' +
            'Read Article <span aria-hidden="true">&rarr;</span></a>' +
        '</div>';
    }

    function matches() {
      var q = state.q.trim().toLowerCase();
      var browsing = !q && state.cat === "All";
      return D.posts.filter(function (p) {
        if (browsing && p === featured) return false;
        if (state.cat !== "All" && p.category !== state.cat) return false;
        if (!q) return true;
        return (p.title + " " + p.excerpt + " " + p.category).toLowerCase().indexOf(q) > -1;
      });
    }

    function pagerHtml(pages) {
      if (pages < 2) return "";
      var out = '<button class="fs-pager__step" type="button" data-page="' + (state.page - 1) + '"' +
        (state.page === 1 ? " disabled" : "") + '><span aria-hidden="true">&laquo;</span> Previous</button>' +
        '<span class="fs-pager__nums">';
      var shown = [];
      for (var i = 1; i <= pages; i++) {
        if (i === 1 || i === pages || Math.abs(i - state.page) <= 1) shown.push(i);
      }
      shown.forEach(function (n, idx) {
        if (idx && n - shown[idx - 1] > 1) out += '<span class="fs-pager__gap" aria-hidden="true">&hellip;</span>';
        out += '<button class="fs-pager__num" type="button" data-page="' + n + '"' +
          (n === state.page ? ' aria-current="page"' : "") + '>' + n + "</button>";
      });
      return out + "</span>" +
        '<button class="fs-pager__step" type="button" data-page="' + (state.page + 1) + '"' +
        (state.page === pages ? " disabled" : "") + '>Next <span aria-hidden="true">&raquo;</span></button>';
    }

    function render() {
      var list = matches();
      var pages = Math.max(1, Math.ceil(list.length / PER));
      if (state.page > pages) state.page = pages;
      var slice = list.slice((state.page - 1) * PER, state.page * PER);

      grid.innerHTML = slice.map(postCard).join("");
      grid.hidden = !slice.length;
      if (emptyEl) emptyEl.hidden = slice.length > 0;
      if (pagerEl) { pagerEl.innerHTML = pagerHtml(pages); pagerEl.hidden = pages < 2; }
      if (countEl) {
        countEl.textContent = list.length + (list.length === 1 ? " article" : " articles") +
          (pages > 1 ? " \u00b7 page " + state.page + " of " + pages : "");
      }
      if (featureEl) {
        var sec = featureEl.closest("section");
        if (sec) sec.hidden = !(!state.q.trim() && state.cat === "All");
      }
      reveal(grid);
    }

    on(pillHost, "click", function (e) {
      var b = e.target.closest("[data-cat]");
      if (!b) return;
      state.cat = b.dataset.cat;
      state.page = 1;
      $$("[data-cat]", pillHost).forEach(function (n) {
        var on_ = n === b;
        n.setAttribute("aria-selected", on_);
        n.setAttribute("aria-pressed", on_);
      });
      render();
    });

    var timer = null;
    on(searchInput, "input", function () {
      clearTimeout(timer);
      timer = setTimeout(function () { state.q = searchInput.value; state.page = 1; render(); }, 180);
    });
    on($("[data-fs-post-search-form]"), "submit", function (e) {
      e.preventDefault();
      clearTimeout(timer);
      state.q = searchInput ? searchInput.value : "";
      state.page = 1;
      render();
    });
    on($("[data-fs-post-clear]"), "click", function () {
      state.q = ""; state.cat = "All"; state.page = 1;
      if (searchInput) searchInput.value = "";
      $$("[data-cat]", pillHost).forEach(function (n) {
        var on_ = n.dataset.cat === "All";
        n.setAttribute("aria-selected", on_);
        n.setAttribute("aria-pressed", on_);
      });
      render();
    });
    on(pagerEl, "click", function (e) {
      var b = e.target.closest("[data-page]");
      if (!b || b.disabled) return;
      state.page = parseInt(b.dataset.page, 10);
      render();
      var top = $("[data-fs-post-grid-top]");
      if (top) window.scrollTo({ top: top.getBoundingClientRect().top + window.scrollY - 90, behavior: "smooth" });
    });

    render();
  }

  /* ---------------------------------------------------- Single blog post --- */

  function proseHtml(blocks) {
    return blocks.map(function (b) {
      if (b.t === "h2") return "<h2>" + esc(b.x) + "</h2>";
      if (b.t === "h3") return "<h3>" + esc(b.x) + "</h3>";
      if (b.t === "p") return "<p>" + esc(b.x) + "</p>";
      if (b.t === "quote") {
        return '<blockquote class="fs-pull"><p>' + esc(b.x) + "</p>" +
          (b.cite ? "<cite>" + esc(b.cite) + "</cite>" : "") + "</blockquote>";
      }
      if (b.t === "ul" || b.t === "ol") {
        // List items carry inline <strong> emphasis authored in the record.
        return "<" + b.t + ' class="fs-prose__list">' +
          b.items.map(function (i) { return "<li>" + i + "</li>"; }).join("") + "</" + b.t + ">";
      }
      if (b.t === "callout") {
        return '<aside class="fs-callout"><p class="fs-callout__label">' + esc(b.label) + "</p>" +
          "<p>" + esc(b.x) + "</p></aside>";
      }
      if (b.t === "img") {
        return "<figure><img src=\"" + esc(b.src) + "\" alt=\"" + esc(b.cap) + "\" loading=\"lazy\" />" +
          "<figcaption>" + esc(b.cap) + "</figcaption></figure>";
      }
      return "";
    }).join("");
  }

  function initBlogPost() {
    var root = $("[data-fs-post-article]");
    if (!root) return;
    var slug = param("post");
    var post = D.posts.filter(function (p) { return p.slug === slug; })[0] || D.posts[0];
    var url = window.location.origin + window.location.pathname + "?post=" + encodeURIComponent(post.slug);

    document.title = post.title + " \u2014 Focused Schools";
    var desc = $('meta[name="description"]');
    if (desc) desc.setAttribute("content", post.excerpt);

    var crumbCat = $("[data-fs-post-crumb-cat]");
    if (crumbCat) {
      crumbCat.textContent = post.category;
      crumbCat.setAttribute("href", "blog.html?cat=" + encodeURIComponent(post.category));
    }
    var crumb = $("[data-fs-post-crumb]");
    if (crumb) crumb.textContent = post.title;

    var media = $("[data-fs-post-media]");
    if (media) {
      media.innerHTML = '<img src="' + esc(post.image) + '" alt="' + esc(post.title) +
        '" width="2400" height="1030" fetchpriority="high" />' +
        '<span class="fs-hero__bracket" aria-hidden="true"></span>';
    }
    var badges = $("[data-fs-post-badges]");
    if (badges) {
      badges.innerHTML = '<span class="fs-badge">' + esc(post.category) + "</span>" +
        '<span class="fs-badge fs-badge--quiet">' + post.read + " min read</span>";
    }
    var title = $("[data-fs-post-title]");
    if (title) title.textContent = post.title;
    var lede = $("[data-fs-post-lede]");
    if (lede) lede.textContent = post.excerpt;

    var byline = $("[data-fs-post-byline]");
    if (byline) {
      byline.innerHTML =
        '<img class="fs-byline__avatar" src="' + esc(D.blogAuthor.image) + '" alt="" width="96" height="96" loading="lazy" />' +
        '<div>' +
          '<p class="fs-byline__name">' + esc(D.blogAuthor.name) + '</p>' +
          '<p class="fs-byline__line">' + esc(D.blogAuthor.line) + '</p>' +
        '</div>' +
        '<p class="fs-byline__when">' +
          '<time datetime="' + esc(post.date) + '">' + esc(post.dateLabel) + '</time>' +
          '<span aria-hidden="true">&middot;</span>' + post.read + ' min read' +
        '</p>';
    }

    root.innerHTML = proseHtml(post.body);

    var share = $("[data-fs-post-share]");
    if (share) {
      var enc = encodeURIComponent(url);
      var encT = encodeURIComponent(post.title);
      share.innerHTML =
        '<a class="fs-share__btn" href="https://www.facebook.com/sharer/sharer.php?u=' + enc + '" target="_blank" rel="noopener" aria-label="Share on Facebook"><span aria-hidden="true">f</span></a>' +
        '<a class="fs-share__btn" href="https://twitter.com/intent/tweet?url=' + enc + '&text=' + encT + '" target="_blank" rel="noopener" aria-label="Share on X"><span aria-hidden="true">X</span></a>' +
        '<a class="fs-share__btn" href="https://www.linkedin.com/sharing/share-offsite/?url=' + enc + '" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><span aria-hidden="true">in</span></a>' +
        '<a class="fs-share__btn" href="mailto:?subject=' + encT + '&body=' + enc + '" aria-label="Share by email"><span aria-hidden="true">@</span></a>' +
        '<button class="fs-share__btn fs-share__btn--wide" type="button" data-fs-copy aria-label="Copy link to this article"><span aria-hidden="true">Copy</span></button>' +
        '<span class="fs-share__toast" role="status" aria-live="polite" data-fs-copy-toast></span>';

      on(share, "click", function (e) {
        if (!e.target.closest("[data-fs-copy]")) return;
        var toast = $("[data-fs-copy-toast]", share);
        function done(msg) { if (toast) { toast.textContent = msg; setTimeout(function () { toast.textContent = ""; }, 2400); } }
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(function () { done("Link copied"); }, function () { done(url); });
        } else { done(url); }
      });
    }

    var bio = $("[data-fs-post-author]");
    if (bio) {
      bio.innerHTML =
        '<img class="fs-authorbox__photo" src="' + esc(D.blogAuthor.image) + '" alt="" width="240" height="240" loading="lazy" />' +
        '<div>' +
          '<p class="fs-eyebrow fs-mb">About the author</p>' +
          '<h3 class="fs-authorbox__name">' + esc(D.blogAuthor.name) + ' &mdash; ' + esc(D.blogAuthor.line) + '</h3>' +
          '<p class="fs-body" style="max-width:62ch;margin-bottom:26px">' + esc(D.blogAuthor.bio) + '</p>' +
          '<a class="fs-btn fs-btn--secondary" href="team.html">Meet Our Team <span aria-hidden="true">&rarr;</span></a>' +
        '</div>';
    }

    var related = $("[data-fs-post-related]");
    if (related) {
      var same = D.posts.filter(function (p) { return p.slug !== post.slug && p.category === post.category; });
      var rest = D.posts.filter(function (p) { return p.slug !== post.slug && p.category !== post.category; });
      related.innerHTML = same.concat(rest).slice(0, 3).map(postCard).join("");
    }
    reveal();
  }

  /* -------------------------------------- Shared contact form (blog pages) --- */

  function initBlogContact() {
    var host = $("[data-fs-mini-contact]");
    if (!host) return;

    var FIELDS = [
      { id: "bc-name", name: "name", label: "Name", type: "text", ac: "name", ph: "Jane Rivera", req: true },
      { id: "bc-phone", name: "phone", label: "Phone", type: "tel", ac: "tel", ph: "(844) 957-2466", req: false },
      { id: "bc-email", name: "email", label: "Email", type: "email", ac: "email", ph: "jrivera@district.org", req: true }
    ];

    host.innerHTML =
      '<div class="fs-panel fs-form-shell" data-mini-shell>' +
        '<header>' +
          '<h2>Contact us</h2>' +
          '<p class="fs-small" style="max-width:56ch">Tell us where your district is headed and what is getting in the way. Fields marked <span class="fs-req">*</span> are required.</p>' +
        '</header>' +
        '<form class="fs-form" novalidate data-mini-form>' +
          '<div class="fs-form__row">' +
            FIELDS.slice(0, 2).map(fieldHtml).join("") +
          '</div>' +
          fieldHtml(FIELDS[2]) +
          '<div class="fs-field" data-field="message">' +
            '<label class="fs-field__label" for="bc-message"><span>Message <span class="fs-req">*</span></span></label>' +
            '<textarea class="fs-textarea" id="bc-message" name="message" rows="5" aria-required="true" aria-describedby="bc-message-msg" placeholder="What are you working on this year?"></textarea>' +
            '<p class="fs-field__msg" id="bc-message-msg"></p>' +
          '</div>' +
          '<div class="fs-submit-row">' +
            '<button class="fs-btn fs-btn--primary" type="submit">Submit <span aria-hidden="true">&rarr;</span></button>' +
            '<p>We reply within two business days. No lists, no newsletters you did not ask for.</p>' +
          '</div>' +
        '</form>' +
      '</div>' +
      '<div class="fs-panel fs-success" role="status" aria-live="polite" data-mini-success hidden>' +
        '<span class="fs-success__check" aria-hidden="true">&#10003;</span>' +
        '<p class="fs-eyebrow" style="margin-bottom:14px">Message sent</p>' +
        '<h2>Thank you &mdash; we have your message.</h2>' +
        '<p class="fs-body" style="max-width:56ch">A member of our team will reply within two business days.</p>' +
      '</div>';

    function fieldHtml(f) {
      return '<div class="fs-field" data-field="' + f.name + '">' +
        '<label class="fs-field__label" for="' + f.id + '"><span>' + f.label +
          (f.req ? ' <span class="fs-req">*</span>' : "") + "</span>" +
          (f.req ? "" : '<small class="fs-opt">Optional</small>') + "</label>" +
        '<input class="fs-input" id="' + f.id + '" name="' + f.name + '" type="' + f.type +
          '" autocomplete="' + f.ac + '"' + (f.req ? ' aria-required="true"' : "") +
          ' aria-describedby="' + f.id + '-msg" placeholder="' + f.ph + '" />' +
        '<p class="fs-field__msg" id="' + f.id + '-msg"></p>' +
      "</div>";
    }

    var form = $("[data-mini-form]", host);
    function setError(name, msg) {
      var wrap = $('[data-field="' + name + '"]', host);
      if (!wrap) return;
      var input = $("input, textarea", wrap);
      wrap.setAttribute("data-error", msg ? "true" : "false");
      if (input) input.setAttribute("aria-invalid", msg ? "true" : "false");
      var m = $(".fs-field__msg", wrap);
      if (m) m.textContent = msg || "";
    }

    on(form, "submit", function (e) {
      e.preventDefault();
      var v = {
        name: $("#bc-name", host).value.trim(),
        email: $("#bc-email", host).value.trim(),
        message: $("#bc-message", host).value.trim()
      };
      var bad = null;
      setError("name", v.name ? "" : (bad = bad || "name", "Please tell us your name."));
      setError("email", /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.email) ? "" : (bad = bad || "email", "Enter a valid email address."));
      setError("message", v.message ? "" : (bad = bad || "message", "Let us know what you are working on."));
      if (bad) {
        var first = $('[data-field="' + bad + '"] input, [data-field="' + bad + '"] textarea', host);
        if (first) first.focus();
        return;
      }
      $("[data-mini-shell]", host).hidden = true;
      var ok = $("[data-mini-success]", host);
      ok.hidden = false;
      ok.setAttribute("tabindex", "-1");
      ok.focus();
    });
  }

  /* ------------------------------------------------- Video lightbox --- */

  // Built once, on first use. Closing clears the iframe src, which is what
  // actually stops YouTube playback.
  var videoModal = null;

  function buildVideoModal() {
    var el = document.createElement("div");
    el.className = "fs-vmodal";
    el.setAttribute("data-fs-vmodal", "");
    el.hidden = true;
    el.innerHTML =
      '<div class="fs-vmodal__backdrop" data-vmodal-close></div>' +
      '<div class="fs-vmodal__dialog" role="dialog" aria-modal="true" aria-label="Video">' +
        '<div class="fs-vmodal__bar">' +
          '<p class="fs-vmodal__title" tabindex="-1"></p>' +
          '<button class="fs-vmodal__close" type="button" data-vmodal-close aria-label="Close video">' +
            '<span aria-hidden="true">&times;</span>' +
          '</button>' +
        '</div>' +
        '<div class="fs-vmodal__frame"></div>' +
      '</div>';
    document.body.appendChild(el);
    return el;
  }

  var videoModalReady = false;

  function initVideoModal() {
    if (videoModalReady) return;
    videoModalReady = true;

    on(document, "click", function (e) {
      var trigger = e.target.closest("[data-video-modal]");
      if (trigger) { openVideoModal(trigger); return; }
      if (e.target.closest("[data-vmodal-close]")) closeVideoModal();
    });

    on(document, "keydown", function (e) {
      if (!videoModal || videoModal.hidden) return;
      if (e.key === "Escape") { closeVideoModal(); return; }
      if (e.key !== "Tab") return;
      var f = $$("button, iframe", videoModal).filter(function (n) { return n.offsetParent !== null; });
      if (!f.length) return;
      var first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  }

  var vmodalTrigger = null;

  function openVideoModal(trigger) {
    if (!videoModal) videoModal = buildVideoModal();
    vmodalTrigger = trigger;
    var id = trigger.getAttribute("data-video-modal");
    var title = trigger.getAttribute("data-video-title") || "Video";

    $(".fs-vmodal__title", videoModal).textContent = title;
    $(".fs-vmodal__frame", videoModal).innerHTML =
      '<iframe src="https://www.youtube.com/embed/' + encodeURIComponent(id) +
        '?autoplay=1&rel=0&playsinline=1" title="' + esc(title) +
        '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ' +
        'allowfullscreen></iframe>';

    videoModal.hidden = false;
    document.body.style.overflow = "hidden";
    $(".fs-vmodal__title", videoModal).focus();
  }

  function closeVideoModal() {
    if (!videoModal || videoModal.hidden) return;
    videoModal.hidden = true;
    $(".fs-vmodal__frame", videoModal).innerHTML = "";
    document.body.style.overflow = "";
    if (vmodalTrigger) vmodalTrigger.focus();
  }

  /* ------------------------------------ Video facades (podcast + lanes) --- */

  // Registered once at boot: the delegated handlers serve every [data-video]
  // card on the page, podcast grid and Services lane alike.
  var videoFacadesReady = false;

  function initVideoFacades() {
    if (videoFacadesReady) return;
    videoFacadesReady = true;

    // Facade → iframe, one player at a time, no layout shift.
    var mounted = null;
    var preconnected = false;
    function preconnect() {
      if (preconnected) return;
      preconnected = true;
      ["https://www.youtube-nocookie.com", "https://i.ytimg.com"].forEach(function (href) {
        var l = document.createElement("link");
        l.rel = "preconnect";
        l.href = href;
        document.head.appendChild(l);
      });
    }
    on(document, "pointerenter", function (e) {
      if (e.target.closest && e.target.closest("[data-video-play]")) preconnect();
    }, true);

    on(document, "click", function (e) {
      var trig = e.target.closest("[data-video-play]");
      if (!trig) return;
      var card = trig.closest("[data-video]");
      if (!card) return;
      var id = card.getAttribute("data-video");
      if (!id) return; // no ID yet → let the link go to YouTube
      e.preventDefault();
      var shell = $("[data-video-shell]", card);
      if (!shell || shell.getAttribute("data-mounted") === "true") return;
      if (mounted && mounted !== shell) unmount(mounted);
      shell.setAttribute("data-mounted", "true");
      shell.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + encodeURIComponent(id) +
        '?autoplay=1&rel=0" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
      mounted = shell;
      var frame = $("iframe", shell);
      if (frame) frame.focus();
      var label = $("[data-video-play] ", card);
      var watch = $(".fs-card__foot .fs-link", card);
      if (watch) watch.firstChild.textContent = "Now playing ";
    });

    function unmount(shell) {
      var card = shell.closest("[data-video]");
      var v = D.videos.filter(function (x) { return x.youtubeId === card.getAttribute("data-video"); })[0] ||
        { thumbnail: card.getAttribute("data-poster") || "", title: card.getAttribute("data-title") || "", duration: "" };
      shell.removeAttribute("data-mounted");
      shell.innerHTML = (v && v.thumbnail
        ? '<img class="fs-media__photo" src="' + esc(v.thumbnail) + '" alt="Video thumbnail: ' + esc(v.title) + '" loading="lazy" />'
        : '<div class="fs-media fs-media--16x9" style="position:absolute;inset:0">' + mediaFallback("") + '</div>') +
        '<a class="fs-video__facade" href="#" data-video-play aria-label="Watch"><span class="fs-video__btn" aria-hidden="true">&#9654;</span></a>' +
        '<span class="fs-video__dim" aria-hidden="true"></span>' +
        (v && v.duration ? '<span class="fs-video__dur" aria-hidden="true">' + esc(v.duration) + '</span>' : "");
      if (mounted === shell) mounted = null;
    }

    on(document, "keydown", function (e) {
      if (e.key === "Escape" && mounted) {
        var card = mounted.closest("[data-video]");
        unmount(mounted);
        var btn = $("[data-video-play]", card);
        if (btn) btn.focus();
      }
    });
  }

  /* -------------------------------------------- Partner map (Leaflet) --- */

  var PIN_SVG =
    '<svg viewBox="0 0 28 38" aria-hidden="true">' +
      '<path class="fs-pin__body" d="M14 0C6.268 0 0 6.268 0 14c0 9.8 11.55 21.9 13.31 23.69a.97.97 0 0 0 1.38 0C16.45 35.9 28 23.8 28 14 28 6.268 21.732 0 14 0Z"/>' +
      '<circle cx="14" cy="13.6" r="5" fill="#ffffff"/>' +
    '</svg>';

  function popupHtml(loc) {
    function group(label, items, past) {
      if (!items.length) return "";
      return '<div class="fs-mappop__group' + (past ? " fs-mappop__group--past" : "") + '">' +
        '<p class="fs-mappop__label">' + esc(label) + ' <b>' + items.length + '</b></p>' +
        '<ul>' + items.map(function (d) { return "<li>" + esc(d) + "</li>"; }).join("") + "</ul>" +
      "</div>";
    }
    return '<div class="fs-mappop">' +
      '<div class="fs-mappop__head">' +
        '<h4 class="fs-mappop__state">' + esc(loc.state) + "</h4>" +
        '<span class="fs-mappop__count">' + esc(loc.summary) + "</span>" +
      "</div>" +
      '<div class="fs-mappop__body">' +
        group(loc.current.length === 1 ? "Current client" : "Current clients", loc.current, false) +
        group(loc.previous.length === 1 ? "Previous client" : "Previous clients", loc.previous, true) +
        group(loc.projects.length === 1 ? "Project" : "Projects", loc.projects, false) +
      "</div>" +
    "</div>";
  }

  function buildPartnerMap(host) {
    var L = window.L;
    var map = L.map(host, {
      center: [39.8, -98.5],
      zoom: 4,
      scrollWheelZoom: false,
      zoomControl: true,
      attributionControl: true
    });
    map.scrollWheelZoom.disable();
    // Wheel zoom only once the map has focus, so the page still scrolls past it.
    on(host, "click", function () { map.scrollWheelZoom.enable(); });
    on(host, "mouseleave", function () { map.scrollWheelZoom.disable(); });

    // Esri World Light Gray Base: keyless, already muted, and unlike OSM's public
    // server it permits third-party embeds. Note the {z}/{y}/{x} coordinate order.
    L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}", {
      maxZoom: 12,
      minZoom: 2,
      attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ'
    }).addTo(map);

    var pts = [];
    D.partnerMap.forEach(function (loc) {
      var isCurrent = loc.current.length > 0;
      var marker = L.marker([loc.lat, loc.lng], {
        riseOnHover: true,
        keyboard: true,
        title: loc.state,
        alt: loc.state + " partner locations",
        icon: L.divIcon({
          className: "fs-pin" + (isCurrent ? " fs-pin--current" : ""),
          html: PIN_SVG,
          iconSize: [28, 38],
          iconAnchor: [14, 38],
          popupAnchor: [0, -34],
          tooltipAnchor: [0, -34]
        })
      });
      marker.bindTooltip(loc.state, { direction: "top", offset: [0, 0], className: "fs-tip", opacity: 1 });
      marker.bindPopup(popupHtml(loc), { className: "fs-pop", maxWidth: 296, minWidth: 296, autoPanPadding: [28, 28] });
      marker.addTo(map);
      pts.push([loc.lat, loc.lng]);
    });

    // The container can still measure 0 on first paint (reveal animations, hidden
    // routes), which makes Leaflet clamp the fit to maxZoom. Size it first, then
    // re-fit on the first non-zero measurement.
    var bounds = L.latLngBounds(pts);
    var fitOpts = { padding: [52, 52], maxZoom: 6, animate: false };

    function fit() {
      map.invalidateSize({ animate: false });
      map.fitBounds(bounds, fitOpts);
    }
    fit();

    if (window.ResizeObserver) {
      var ro = new ResizeObserver(function () {
        if (!host.clientWidth || !host.clientHeight) return;
        fit();
        ro.disconnect();
      });
      ro.observe(host);
    } else {
      setTimeout(fit, 240);
    }
  }

  function initPartnerMap() {
    var hosts = $$("[data-fs-partner-map]").filter(function (h) {
      return h.getAttribute("data-ready") !== "true";
    });
    if (!hosts.length) return;
    if (!window.L) {
      // Leaflet still loading — retry once everything has settled.
      on(window, "load", initPartnerMap);
      return;
    }
    hosts.forEach(function (host) {
      host.setAttribute("data-ready", "true");
      buildPartnerMap(host);
    });
  }

  /* ------------------------------------------------------------ Podcast --- */

  function initVideos() {
    var paged = pagedGrid({
      grid: "[data-fs-video-grid]",
      more: "[data-fs-video-more]",
      count: "[data-fs-video-count]",
      empty: "[data-fs-video-empty]",
      size: PAGE_SIZE.videos,
      items: D.videos,
      card: videoCard
    });
    if (paged) paged.render();


    // Episode rows (styled stand-in for the Buzzsprout list embed).
    var list = $("[data-fs-episodes]");
    if (list) {
      list.innerHTML = D.episodes.map(function (e) {
        return '<div class="fs-ep-row">' +
          '<button class="fs-play" type="button" data-audio="ep' + e.n + '" aria-label="Play episode ' + e.n + '">&#9654;</button>' +
          '<div style="min-width:0">' +
            '<p class="fs-eyebrow fs-eyebrow--rasp" style="font-size:11px;letter-spacing:.12em">Episode ' + e.n + '</p>' +
            '<h3 class="fs-clamp-2">' + esc(e.title) + '</h3>' +
            '<p class="fs-mini">' + esc(e.date) + '</p>' +
          '</div>' +
          '<span class="fs-ep-row__dur">' + esc(e.duration) + '</span>' +
        '</div>';
      }).join("");
    }

    // Latest-episode player state (Buzzsprout owns real playback in production).
    var playing = null;
    on(document, "click", function (e) {
      var b = e.target.closest("[data-audio]");
      if (!b) return;
      var key = b.dataset.audio;
      var isOn = playing === key;
      $$("[data-audio]").forEach(function (n) {
        n.innerHTML = "&#9654;";
        n.style.paddingLeft = "3px";
        n.setAttribute("aria-label", n.getAttribute("aria-label").replace("Pause", "Play"));
      });
      var bar = $("[data-fs-audio-bar]");
      var state = $("[data-fs-audio-state]");
      var track = $("[data-fs-audio-track]");
      if (isOn) {
        playing = null;
        if (bar) bar.style.width = "0%";
        if (track) track.setAttribute("aria-valuenow", "0");
        if (state) state.textContent = "Play episode";
      } else {
        playing = key;
        b.innerHTML = "&#10073;&#10073;";
        b.style.paddingLeft = "0";
        if (key === "latest") {
          if (bar) bar.style.width = "34%";
          if (track) track.setAttribute("aria-valuenow", "34");
          if (state) state.textContent = "Playing";
        }
      }
    });
  }

  /* ----------------------------------------------------------- Services --- */

  function initServices() {
    var index = $("[data-fs-lane-index]");
    if (index) {
      index.innerHTML = D.services.map(function (s, i) {
        return '<a class="fs-index__row" href="#' + esc(s.slug) + '">' +
          '<span class="fs-index__num">' + pad(i + 1) + '</span>' +
          '<span class="fs-index__title">' + esc(s.title) + '</span>' +
          '<span class="fs-index__tag">' + esc(s.tagline) + '</span>' +
          '<span class="fs-index__arrow" aria-hidden="true">&rarr;</span>' +
        '</a>';
      }).join("");
    }

    var host = $("[data-fs-lanes]");
    if (!host) return;
    host.innerHTML = D.services.map(function (s, i) {
      var flip = i % 2 === 1;
      var accent = "fs-rule--" + (s.accent || "teal");
      // Lime has insufficient contrast for type: caption kicker falls back to cerulean.
      var kicker = s.accent === "lime" ? "#0A96CB" : "var(--fs-" + (s.accent || "teal") + ")";
      return '<section class="fs-sec fs-sec--lg' + (flip ? " fs-sec--paper" : "") + '" id="' + esc(s.slug) + '">' +
        '<div class="fs-shell">' +
          '<div class="fs-lane' + (flip ? " fs-lane--flip" : "") + '">' +
            '<div class="fs-lane__copy" data-reveal>' +
              '<div class="fs-lane__accent">' +
                '<span class="fs-rule ' + accent + '" aria-hidden="true"></span>' +
                '<span>' + pad(i + 1) + '</span>' +
              '</div>' +
              '<h2>' + esc(s.title) + '</h2>' +
              (s.tagline ? '<p class="fs-lane__tagline">' + esc(s.tagline) + '</p>' : "") +
              '<p class="fs-body fs-lane__desc">' + esc(s.description) + '</p>' +
              (s.offerings && s.offerings.length
                ? '<div class="fs-offerings">' +
                    '<p class="fs-eyebrow fs-offerings__label">Signature offerings</p>' +
                    '<ul' + (s.offerings.length < 4 ? ' style="grid-template-columns:minmax(0,1fr)"' : "") + '>' +
                      s.offerings.map(function (o) {
                        return '<li><span aria-hidden="true" style="background:var(--fs-' + (s.accent || "teal") + ')"></span>' + esc(o) + '</li>';
                      }).join("") +
                    '</ul></div>'
                : "") +
              '<div class="fs-btn-row">' +
                (s.ctaLabel ? '<a class="fs-btn fs-btn--primary" href="contact.html">' + esc(s.ctaLabel) + ' <span aria-hidden="true">&rarr;</span></a>' : "") +
                (s.youtubeId
                  ? '<button class="fs-btn fs-btn--secondary fs-btn--watch" type="button" ' +
                      'data-video-modal="' + esc(s.youtubeId) + '" ' +
                      'data-video-title="' + esc(s.videoTitle || s.title) + '">' +
                      '<span class="fs-btn__play" aria-hidden="true">&#9654;</span>Watch Overview Video' +
                    '</button>'
                  : "") +
              '</div>' +
            '</div>' +
            (s.image
              ? '<figure class="fs-lane__media fs-figure' + (s.youtubeId ? " fs-lane__media--video" : "") + '" data-reveal>' +
                  '<img class="fs-photo" src="' + esc(s.image) + '" alt="' + esc(s.title) + ' partnership work in progress." loading="lazy" width="1000" height="1250" />' +
                  (s.youtubeId
                    ? '<button class="fs-playover" type="button" ' +
                        'data-video-modal="' + esc(s.youtubeId) + '" ' +
                        'data-video-title="' + esc(s.videoTitle || s.title) + '">' +
                        '<span class="fs-playover__disc" aria-hidden="true">&#9654;</span>' +
                        '<span class="fs-playover__label">Watch Overview Video</span>' +
                      '</button>'
                    : "") +
                  '<figcaption class="fs-figure__caption' + (flip ? " fs-figure__caption--left" : "") + '">' +
                    '<span class="fs-figure__kicker" style="color:' + kicker + '">' + esc(s.title) + '</span>' +
                    '<strong>' + esc(s.caption) + '</strong>' +
                  '</figcaption>' +
                '</figure>'
              : "") +
          '</div>' +
        '</div>' +
      '</section>';
    }).join("");
    reveal(host);
  }

  /* ---------------------------------------------- Shared list renderers --- */

  function initShared() {
    var statsHost = $("[data-fs-stats]");
    if (statsHost) {
      statsHost.innerHTML = D.stats.map(function (s) {
        return '<article class="fs-stat" data-reveal>' +
          '<p class="fs-stat__fig" aria-label="' + esc(s.aria) + '">' +
            '<span class="fs-stat__value">' + esc(s.value) + '</span>' +
            '<strong class="fs-stat__unit">' + esc(s.unit) + '</strong>' +
          '</p>' +
          '<h3 class="fs-stat__label">' + esc(s.label) + '</h3>' +
        '</article>';
      }).join("");
    }

    var partnerHost = $("[data-fs-partners]");
    if (partnerHost) {
      partnerHost.innerHTML = D.partners.map(function (p, i) {
        return '<div class="fs-state-row">' +
          '<div class="fs-state-row__label">' +
            '<span>' + pad(i + 1) + '</span><h3>' + esc(p.state) + '</h3>' +
          '</div>' +
          '<ul class="fs-chips">' +
            p.districts.map(function (d) {
              return '<li><span aria-hidden="true"></span>' + esc(d) + '</li>';
            }).join("") +
          '</ul>' +
        '</div>';
      }).join("");
    }

    var homeStories = $("[data-fs-home-stories]");
    if (homeStories) {
      homeStories.innerHTML = D.stories.slice(0, 3).map(storyCard).join("");
    }

    var homeServices = $("[data-fs-home-services]");
    if (homeServices) {
      homeServices.innerHTML = D.services.map(function (s, i) {
        return '<a class="fs-index__row" href="services.html#' + esc(s.slug) + '">' +
          '<span class="fs-index__num">' + pad(i + 1) + '</span>' +
          '<span class="fs-index__title">' + esc(s.title) + '</span>' +
          '<span class="fs-index__tag">' + esc(s.tagline) + '</span>' +
          '<span class="fs-index__arrow" aria-hidden="true">&rarr;</span>' +
        '</a>';
      }).join("");
    }
  }

  /* -------------------------------------------------------- Contact form --- */

  var RULES = {
    name: { label: "Full name", msg: "Full name is required.", test: function (v) { return v.trim().length > 0; } },
    email: {
      label: "Email",
      msg: "Enter a valid email address so we can reply.",
      test: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()); }
    },
    organization: { label: "District or school", msg: "Tell us which district or school you are writing from.", test: function (v) { return v.trim().length > 0; } },
    message: {
      label: "How can we help?",
      msg: "Tell us a little about how we can help \u2014 at least a sentence.",
      test: function (v) { return v.trim().length >= 20; }
    }
  };

  function initContact() {
    var form = $("[data-fs-form]");
    if (!form) return;
    var shell = $("[data-fs-form-shell]");
    var success = $("[data-fs-success]");
    var summary = $("[data-fs-summary]");
    var submit = $("[data-fs-submit]");
    var counter = $("[data-fs-counter]");
    var msgField = form.elements.message;

    function wrap(input) { return input.closest(".fs-field"); }

    function setError(input, message) {
      var f = wrap(input);
      if (!f) return;
      f.setAttribute("data-error", "true");
      input.setAttribute("aria-invalid", "true");
      var m = $(".fs-field__msg", f);
      if (m) m.textContent = message;
    }
    function clearError(input) {
      var f = wrap(input);
      if (!f) return;
      f.removeAttribute("data-error");
      input.setAttribute("aria-invalid", "false");
      var m = $(".fs-field__msg", f);
      if (m) m.textContent = m.getAttribute("data-help") || "";
    }
    function validate(input) {
      var rule = RULES[input.name];
      if (!rule) return true;
      if (rule.test(input.value)) { clearError(input); return true; }
      setError(input, rule.msg);
      return false;
    }

    Object.keys(RULES).forEach(function (name) {
      var input = form.elements[name];
      if (!input) return;
      // Validate on blur, never on keystroke.
      on(input, "blur", function () { validate(input); });
      // Re-validate on input only once the field is already in error.
      on(input, "input", function () {
        if (wrap(input) && wrap(input).getAttribute("data-error") === "true") validate(input);
      });
    });

    if (msgField && counter) {
      on(msgField, "input", function () {
        var n = msgField.value.trim().length;
        counter.textContent = n > 20 ? n + " / 1500" : "Optional detail helps";
      });
    }

    on(form, "submit", function (e) {
      e.preventDefault();
      var failed = Object.keys(RULES).map(function (name) {
        var input = form.elements[name];
        return input && !validate(input) ? { input: input, rule: RULES[name] } : null;
      }).filter(Boolean);

      if (failed.length) {
        summary.hidden = false;
        $("[data-fs-summary-head]", summary).textContent =
          failed.length === 1
            ? "One field needs your attention before we can send this."
            : failed.length + " fields need your attention before we can send this.";
        $("[data-fs-summary-list]", summary).innerHTML = failed.map(function (f) {
          return '<li><a href="#' + f.input.id + '">' + esc(f.rule.msg.replace(/\.$/, "")) + '</a></li>';
        }).join("");
        summary.setAttribute("tabindex", "-1");
        summary.focus();
        return;
      }

      summary.hidden = true;
      submit.disabled = true;
      submit.setAttribute("aria-busy", "true");
      submit.textContent = "Sending\u2026";

      // Production: POST to the form plugin's REST endpoint, then render this
      // panel from its AJAX confirmation (see docs/page-specs/contact.md §11).
      setTimeout(function () {
        shell.hidden = true;
        success.hidden = false;
        success.setAttribute("tabindex", "-1");
        success.focus();
        if (window.dataLayer) window.dataLayer.push({ event: "contact_form_success" });
      }, 700);
    });

    on(summary, "click", function (e) {
      var a = e.target.closest("a[href^='#']");
      if (!a) return;
      e.preventDefault();
      var target = document.getElementById(a.getAttribute("href").slice(1));
      if (target) target.focus();
    });
  }

  /* ----------------------------------------------------------------- boot --- */

  function boot() {
    // The single-file build owns its own boot sequence via router.js.
    if (document.body.hasAttribute("data-fs-router")) return;
    var page = document.body.getAttribute("data-page") || "";
    mountChrome(page);
    initShared();
    if (page === "impact") initStories();
    if (page === "story") initSingleStory();
    if (page === "team") initTeam();
    initBioDialog();
    initVideoFacades();
    initVideoModal();
    initPartnerMap();
    if (page === "podcast") initVideos();
    if (page === "services") initServices();
    if (page === "contact") initContact();
    if (page === "blog") initBlog();
    if (page === "blog-post") initBlogPost();
    initBlogContact();
    reveal();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }

  window.FS = {
    reveal: reveal,
    storyCard: storyCard,
    teamCard: teamCard,
    videoCard: videoCard,
    mountChrome: mountChrome,
    initShared: initShared,
    initStories: initStories,
    initSingleStory: initSingleStory,
    initTeam: initTeam,
    initBioDialog: initBioDialog,
    initVideos: initVideos,
    initVideoFacades: initVideoFacades,
    initVideoModal: initVideoModal,
    initBlog: initBlog,
    initBlogPost: initBlogPost,
    initBlogContact: initBlogContact,
    initPartnerMap: initPartnerMap,
    initServices: initServices,
    initContact: initContact,
    nav: NAV
  };
})();
