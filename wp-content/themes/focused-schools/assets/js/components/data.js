/* ==========================================================================
   Focused Schools — content data
   Single source of truth for every repeating card on the site.
   In WordPress these arrays are replaced by the CPT queries described in
   docs/page-specs/*. Field names match the specs 1:1.
   ========================================================================== */

window.FS_DATA = (function () {
  var IMG = "https://focused-schools-rebrand.vercel.app/assets/img/";

  /* ---------------------------------------------- Impact stories (CPT) --- */
  // source: "story" = fs_impact_story CPT | "legacy" = legacy WordPress Page
  var stories = [
    {
      slug: "champaign-unit-4",
      title: "A five-year plan the whole district can name",
      district: "Champaign Unit 4",
      state: "IL",
      stateFull: "Illinois",
      year: "2024",
      image: IMG + "retreat-1.jpg",
      featured: true,
      source: "story",
      excerpt: "Two years in, cabinet meetings start with the plan instead of the crisis. Every principal can name the three priorities without looking them up."
    },
    {
      slug: "fitchburg-public-schools",
      title: "Rebuilding instructional leadership after three superintendents in four years",
      district: "Fitchburg Public Schools",
      state: "MA",
      stateFull: "Massachusetts",
      year: "2024",
      image: IMG + "retreat-3.jpg",
      featured: false,
      source: "story",
      excerpt: "Leadership transitions are where improvement usually dies. Fitchburg built a district leadership team that outlasted the turnover."
    },
    {
      slug: "downey-unified",
      title: "Coaches who coach each other",
      district: "Downey Unified School District",
      state: "CA",
      stateFull: "California",
      year: "2023",
      image: "",
      featured: false,
      source: "story",
      excerpt: "An IMPACT Coaching cohort that started with six coaches and ended with a district-wide practice for looking at student work."
    },
    {
      slug: "natick-public-schools",
      title: "One Portrait of a Graduate, written by four hundred people",
      district: "Natick Public Schools",
      state: "MA",
      stateFull: "Massachusetts",
      year: "2023",
      image: "",
      featured: false,
      source: "story",
      excerpt: "A community engagement process that produced a portrait the School Committee adopted unanimously and teachers actually use."
    },
    {
      slug: "rantoul-city-schools",
      title: "Data days that teachers ask for",
      district: "Rantoul City Schools SD 137",
      state: "",
      stateFull: "",
      year: "",
      image: "",
      featured: false,
      source: "legacy",
      excerpt: "A PLC inquiry cycle that changed what happens in the two weeks after a benchmark assessment."
    },
    {
      slug: "capitol-region-education-council",
      title: "Building a leadership pipeline from within",
      district: "Capitol Region Education Council",
      state: "",
      stateFull: "",
      year: "",
      image: "",
      featured: false,
      source: "legacy",
      excerpt: "An aspiring leaders academy that filled seven of nine openings internally."
    },
    {
      slug: "mohawk-trail-regional",
      title: "From compliance to coherence in a rural district",
      district: "Mohawk Trail Regional",
      state: "MA",
      stateFull: "Massachusetts",
      year: "2022",
      image: "",
      featured: false,
      source: "story",
      excerpt: "Two small districts sharing one improvement plan, one calendar, and one set of priorities."
    },
    {
      slug: "medway-public-schools",
      title: "A governance rhythm the School Committee trusts",
      district: "Medway Public Schools",
      state: "MA",
      stateFull: "Massachusetts",
      year: "2022",
      image: "",
      featured: false,
      source: "story",
      excerpt: "Quarterly progress reporting that replaced anecdote with evidence, and cut meeting length in half."
    },
    {
      slug: "san-marino-unified",
      title: "Turning a strategic plan into a Tuesday",
      district: "San Marino Unified School District",
      state: "CA",
      stateFull: "California",
      year: "2021",
      image: "",
      featured: false,
      source: "story",
      excerpt: "Operationalizing a plan across cabinet and directors so the work showed up in weekly decisions."
    }
  ];

  /* ------------------------------------------------------- Team (CPT) --- */
  var team = [
    { name: "Name Surname", role: "Senior Partner, Leadership and Systems", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "The best plans are the ones a district can still run when we are not in the room.", bio: "Before joining Focused Schools, this team member led schools and districts through the same work they now support: building leadership teams, running honest inquiry cycles, and keeping improvement moving through leadership change." },
    { name: "Name Surname", role: "Partner, Strategy and Vision", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "A vision that never reaches a Tuesday afternoon is not a vision. It is a poster.", bio: "Real bios replace this text from the CPT editor field and may run several paragraphs — the dialog scrolls, the page behind it does not." },
    { name: "Name Surname", role: "Director of IMPACT Coaching and Professional Learning Communities", portrait: "", linkedin: "", quote: "Coaching is not telling. It is asking the question the team has been avoiding, and then staying in the room for the answer.", bio: "Real bios replace this text from the CPT editor field." },
    { name: "Name Surname", role: "Senior Consultant, Capacity and Coaching", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "Look hard at student work and the next decision usually makes itself.", bio: "Real bios replace this text from the CPT editor field." },
    { name: "Name Surname", role: "Consultant, Strategy and Vision", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "", bio: "This record has no quote, so the quote block is omitted entirely and the card still aligns with its row." },
    { name: "Name Surname", role: "Director of Partnerships", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "I have sat on the other side of this table. That is why we listen first.", bio: "" },
    { name: "Name Surname", role: "Senior Consultant, Leadership and Systems", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "Leadership transitions are where improvement goes to die. They do not have to be.", bio: "Real bios replace this text from the CPT editor field." },
    { name: "Name Surname", role: "Consultant, Capacity and Coaching", portrait: "", linkedin: "", quote: "PLCs work when the data is honest and the room is safe. Both are built, not assumed.", bio: "Real bios replace this text from the CPT editor field." },
    { name: "Name Surname", role: "Operations and Client Services Manager", portrait: "", linkedin: "https://www.linkedin.com/company/focused-schools", quote: "Every detail we handle is one less thing a principal has to carry.", bio: "Real bios replace this text from the CPT editor field." }
  ];

  /* --------------------------------------------------- Services (CPT) --- */
  var services = [
    {
      slug: "strategy-and-vision",
      title: "Strategy and Vision",
      accent: "cerulean",
      tagline: "A plan your whole district can name, and act on by Tuesday.",
      description: "We work with boards, cabinets, and school leadership teams to build strategy that survives contact with a real school year. Listening comes first: focus groups, existing documents, and the data you already have. What comes out is one plan with a small number of priorities, a progress rhythm the School Committee trusts, and an operational version every principal can hold in one hand.",
      offerings: [
        "Strategic planning and plan refresh",
        "Portrait of a Graduate development",
        "Community and stakeholder engagement",
        "Board and cabinet goal setting",
        "Quarterly progress reporting rhythms",
        "Entry plans for new superintendents"
      ],
      image: IMG + "retreat-1.jpg",
      caption: "Eleven weeks of listening before a single priority was written.",
      youtubeId: "3cj6g0rC7Hw",
      videoTitle: "Strategic Planning",
      ctaLabel: "Start This Conversation"
    },
    {
      slug: "leadership-and-systems",
      title: "Leadership and Systems",
      accent: "coral",
      tagline: "Leadership that holds when the people change.",
      description: "Improvement work usually dies in a transition. We build the systems and the bench strength that keep it moving: district and school leadership teams that run real inquiry cycles, principal supervision that develops rather than inspects, and technical assistance for the operational work underneath it all.",
      offerings: [
        "District leadership team development",
        "Principal supervision and support",
        "Technical assistance and turnaround support",
        "Leadership pipeline and aspiring leaders academies",
        "Governance and decision-making structures",
        "Improvement planning and monitoring"
      ],
      image: IMG + "retreat-3.jpg",
      caption: "Seven of nine openings filled from inside the district.",
      youtubeId: "-KzqBAQ78zc",
      videoTitle: "Leadership Development",
      ctaLabel: "Start This Conversation"
    },
    {
      slug: "capacity-and-coaching",
      title: "Capacity and Coaching",
      accent: "lime",
      tagline: "Build the practice, not the dependency.",
      description: "IMPACT Coaching and professional learning communities that leave a district able to do the work without us. We coach coaches, facilitate the first cycles alongside your teams, and hand over the protocols, the calendar, and the facilitation moves so the practice keeps running after the engagement ends.",
      offerings: [
        "IMPACT Coaching cohorts",
        "Professional learning community design",
        "Looking at student work protocols",
        "Instructional rounds and calibration",
        "Coach-the-coach development",
        "Job-embedded professional development"
      ],
      image: IMG + "retreat-2.jpg",
      caption: "A district-wide practice for looking at student work.",
      youtubeId: "wyi1-nXml5A",
      videoTitle: "Educator Development",
      ctaLabel: "Start This Conversation"
    }
  ];

  /* ------------------------------------------ Podcast episodes (audio) --- */
  var episodes = [
    { n: 14, title: "Leading through a superintendent transition without losing the plan", date: "March 4, 2026", duration: "28:14" },
    { n: 13, title: "What a Portrait of a Graduate actually changes on a Tuesday", date: "February 18, 2026", duration: "34:02" },
    { n: 12, title: "Coaching the coaches", date: "February 4, 2026", duration: "31:47" },
    { n: 11, title: "Data days teachers ask for", date: "January 21, 2026", duration: "26:35" },
    { n: 10, title: "Building a leadership pipeline from inside your own district", date: "January 7, 2026", duration: "30:08" }
  ];

  /* ------------------------------------------ Podcast videos (YouTube) --- */
  // youtubeId: set the real ID per record. Thumbnails must be cached locally
  // in production — never hotlink i.ytimg.com. See docs/page-specs/podcast.md §11.
  var videos = [
    { n: 14, youtubeId: "", title: "Leading through a superintendent transition without losing the plan", date: "March 4, 2026", duration: "28 min", thumbnail: IMG + "retreat-1.jpg" },
    { n: 13, youtubeId: "", title: "What a Portrait of a Graduate actually changes on a Tuesday", date: "February 18, 2026", duration: "34 min", thumbnail: IMG + "retreat-3.jpg" },
    { n: 12, youtubeId: "", title: "Coaching the coaches", date: "February 4, 2026", duration: "31 min", thumbnail: IMG + "retreat-2.jpg" },
    { n: 11, youtubeId: "", title: "Data days teachers ask for", date: "January 21, 2026", duration: "26 min", thumbnail: "" },
    { n: 10, youtubeId: "", title: "Building a leadership pipeline from inside your own district", date: "January 7, 2026", duration: "", thumbnail: "" },
    { n: 9, youtubeId: "", title: "Governance rhythms that replace anecdote with evidence", date: "December 10, 2025", duration: "29 min", thumbnail: "" },
    { n: 8, youtubeId: "", title: "When two small districts share one improvement plan", date: "November 26, 2025", duration: "33 min", thumbnail: "" },
    { n: 7, youtubeId: "", title: "Looking at student work, honestly", date: "November 12, 2025", duration: "27 min", thumbnail: "" }
  ];

  /* -------------------------------------------------------- Blog posts --- */
  var blogCategories = ["Leadership","Data & Improvement","Strategic Planning","Culture & Community"];

  var blogAuthor = {
    "name": "Kerry",
    "line": "On behalf of the Focused Schools Team",
    "bio": "Kerry writes on behalf of the Focused Schools team — educators, former superintendents, principals, and coaches who have sat on the district side of the table. Since 2000 we have partnered with districts across 24 states to strengthen leadership, support educators, and build systems that outlast any single engagement.",
    "image": "https://focused-schools-rebrand.vercel.app/assets/img/retreat-2.jpg"
  };

  var posts = [
    {
      slug: "a-plan-nobody-can-name",
      title: "If nobody can name the plan, you don't have one",
      category: "Strategic Planning",
      date: "2026-08-18", dateLabel: "August 18, 2026", read: 6,
      featured: true,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-1.jpg",
      excerpt: "A strategic plan that lives in a binder is a document. A plan three people in every building can recite from memory is a strategy. The difference is almost never the writing.",
      body: [
        {"t":"p","x":"Most districts we meet do not have a planning problem. They have a plan that nobody can name. Ask a principal what the district's three priorities are and you get a pause, then a guess, then an apology. The document exists. It was expensive. It is thorough. And it is not operating."},
        {"t":"h2","x":"The binder is not the strategy"},
        {"t":"p","x":"A plan becomes real at the moment someone uses it to say no. If your plan has never caused a district to decline a promising initiative, turn down a grant that pulled in a fourth direction, or end a program people liked, it is not yet functioning as a strategy — it is functioning as a wish list with a cover page."},
        {"t":"quote","x":"We had forty-one action items. Forty-one. That is not a plan, that is a list of everything we were already worried about.","cite":"Assistant superintendent, first planning session"},
        {"t":"h2","x":"Three tests for a plan that operates"},
        {"t":"ul","items":["<strong>The recitation test.</strong> Stop three adults in three different buildings. Can each name the priorities without looking? If not, the plan has not left the cabinet.","<strong>The calendar test.</strong> Open the School Committee agenda for the year. Does each priority get a scheduled progress conversation, or only the ones that are going well?","<strong>The budget test.</strong> Trace each priority to a line item. Unfunded priorities are not priorities; they are hopes with a deadline."]},
        {"t":"p","x":"Districts that pass all three tend to have fewer priorities than they started with. That is not a coincidence. Narrowing is the work — and it is uncomfortable, because every item you cut belongs to somebody who fought for it."},
        {"t":"callout","label":"Try this next month","x":"Pull your current plan and highlight every item that has a named owner, a funded line, and a date on the board calendar. Whatever stays unhighlighted is the honest scope of your plan."},
        {"t":"h2","x":"What replaces the binder"},
        {"t":"p","x":"In the districts where planning sticks, the artifact people actually use is smaller than the plan: a single page with three priorities, the measures under each, and the four dates a year when the board hears progress. The full plan still exists. It just is not the thing anyone carries."},
        {"t":"p","x":"Two years in, the best sign of success is that the rhythm runs without us. Nobody asks whether the quarterly review is happening. It is on the calendar, the data is already pulled, and the conversation starts with what changed rather than what was attempted."}
      ]
    },
    {
      slug: "walkthroughs-that-find-something",
      title: "What a good instructional walkthrough is actually looking for",
      category: "Data & Improvement",
      date: "2026-08-04", dateLabel: "August 4, 2026", read: 5,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-3.jpg",
      excerpt: "Most walkthrough tools measure whether adults are doing things. The useful ones measure whether students are doing thinking.",
      body: [
        {"t":"p","x":"Walkthrough instruments tend to grow. Someone adds a look-for, then a second, and within two years the tool has thirty-one boxes and takes nine minutes to complete for a visit that lasted six."},
        {"t":"h2","x":"Count the thinking, not the compliance"},
        {"t":"p","x":"The most common failure is that the tool watches the teacher. Objective posted. Materials distributed. Transitions efficient. All of that can be true in a room where no student has been asked to do anything harder than copy."},
        {"t":"p","x":"The shift that changes conversations: record what students are doing with their minds. Who is talking, and for how long? What is a student allowed to get wrong here? When a student is stuck, what happens next?"},
        {"t":"quote","x":"We ran the same tool for three years and never once had an argument about it. That should have told us something.","cite":"Curriculum director, Massachusetts"},
        {"t":"h2","x":"Three questions that beat a thirty-item rubric"},
        {"t":"ul","items":["What is the task actually asking a student to do — recall, apply, or decide?","Who is doing the cognitive work in this room right now?","If a student did not understand, how would anyone in the room find out?"]},
        {"t":"callout","label":"A note on frequency","x":"Two short visits a week that produce a real conversation beat a quarterly sweep that produces a spreadsheet. The value is in the debrief, not the data."},
        {"t":"p","x":"Districts that make this switch usually report the same thing first: the walkthroughs got shorter and the conversations afterward got longer. That is the trade you want."}
      ]
    },
    {
      slug: "grow-your-next-principal",
      title: "Grow your next principal before you need one",
      category: "Leadership",
      date: "2026-07-21", dateLabel: "July 21, 2026", read: 7,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-2.jpg",
      excerpt: "Every district says it wants an internal pipeline. Most start building one the week a principal resigns, which is roughly five years too late.",
      body: [
        {"t":"p","x":"A principal vacancy announced in March is a staffing problem. A principal vacancy you saw coming in the spring two years earlier is a development opportunity. The difference between those two districts is not luck."},
        {"t":"h2","x":"Pipelines are built in ordinary weeks"},
        {"t":"p","x":"The districts that fill most of their openings from inside do a small number of unglamorous things consistently. They name potential early and out loud. They hand real decisions to assistant principals rather than real tasks. And they let emerging leaders fail somewhere the stakes are survivable."},
        {"t":"ol","items":["Name three to five people per year who are being developed, and tell them.","Give each one a decision with consequences — a budget line, a hiring committee, a schedule rebuild.","Pair them with a sitting principal who is evaluated, in part, on their growth.","Review the list every spring and be honest when someone is not ready."]},
        {"t":"quote","x":"Seven of our nine openings last year were filled by people we had been developing for two years. The other two were the ones we did not see coming.","cite":"Superintendent, partner district"},
        {"t":"h2","x":"What gets in the way"},
        {"t":"p","x":"Usually one of two things. Either the district cannot say out loud who it is developing — because naming three people feels like rejecting everyone else — or it develops people generously and then has nowhere to place them, and they leave for a neighboring district that does."},
        {"t":"callout","label":"The retention half of the problem","x":"A pipeline that produces leaders you cannot place is a training program for your competitors. Build the bench and the openings map at the same time."},
        {"t":"p","x":"The payoff is not only speed of hire. Internally grown principals start in August already knowing the district's priorities, its data, and which fights are worth having."}
      ]
    },
    {
      slug: "data-meetings-that-change-tuesday",
      title: "Data meetings that change what happens on Tuesday",
      category: "Data & Improvement",
      date: "2026-07-07", dateLabel: "July 7, 2026", read: 5,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-1.jpg",
      excerpt: "The test of a data meeting is not the quality of the analysis. It is whether a single student's week is different because the meeting happened.",
      body: [
        {"t":"p","x":"Teams can spend ninety minutes with a dashboard and leave with nothing but a shared feeling of concern. The analysis was fine. The meeting still failed."},
        {"t":"h2","x":"End with names, not trends"},
        {"t":"p","x":"A useful data conversation narrows relentlessly: from the grade level, to the standard, to the specific students, to what one teacher will try before Friday. If the meeting ends at the trend, the trend is all you get."},
        {"t":"quote","x":"We stopped asking 'what does the data say' and started asking 'who are we talking about and what are we doing Tuesday.'","cite":"Instructional coach, Illinois"},
        {"t":"h2","x":"A four-move structure"},
        {"t":"ol","items":["<strong>Look.</strong> Ten minutes with student work, not a summary of student work.","<strong>Name.</strong> Which students, specifically, and what is the misconception?","<strong>Choose.</strong> One instructional move, owned by a named person.","<strong>Return.</strong> Put the check-in on next meeting's agenda before you leave this one."]},
        {"t":"callout","label":"Protect the looking","x":"The first ten minutes are the ones teams cut when they run late. They are also the only ten minutes that cannot be replaced by a report."},
        {"t":"p","x":"The structure is not the point. The discipline of ending with a named student, a named adult, and a named date is the point — and it is the part that erodes first when the calendar gets tight."}
      ]
    },
    {
      slug: "first-ninety-days",
      title: "The first ninety days of a superintendency",
      category: "Leadership",
      date: "2026-06-23", dateLabel: "June 23, 2026", read: 6,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-2.jpg",
      excerpt: "The pressure to arrive with answers is enormous. The leaders who last spend the first quarter buying information instead of spending credibility.",
      body: [
        {"t":"p","x":"New superintendents are handed a mandate and a clock. Boards want early wins. Staff want to know what is changing. The temptation is to announce a direction in week three, before you have the standing to sustain it."},
        {"t":"h2","x":"Listening is not a delay tactic"},
        {"t":"p","x":"Eleven weeks of structured listening before a single priority is written sounds slow. In practice it is the fastest route to a plan that survives, because every group that could quietly sink it has already been heard by name."},
        {"t":"ul","items":["Building-level listening sessions with a published question set, not open mics.","One-on-ones with every principal in the first six weeks.","A standing thirty minutes with the board chair, weekly, no agenda required.","A written synthesis shared back publicly — including what you heard that you cannot act on."]},
        {"t":"quote","x":"The synthesis document mattered more than the plan. People saw their own words in it.","cite":"Board chair, partner district"},
        {"t":"h2","x":"The early win problem"},
        {"t":"p","x":"You will need one. Choose something visible, genuinely broken, and inside your control — a bus route, a hiring bottleneck, a facilities complaint people have raised for years. Do not choose curriculum in the first quarter."},
        {"t":"callout","label":"What not to do in month one","x":"Reorganize the cabinet. You do not yet know who is quietly holding the district together."}
      ]
    },
    {
      slug: "family-engagement-is-a-strategy",
      title: "Family engagement is a strategy, not an event",
      category: "Culture & Community",
      date: "2026-06-09", dateLabel: "June 9, 2026", read: 4,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-3.jpg",
      excerpt: "Attendance at the fall open house is a hospitality metric. It tells you almost nothing about whether families can influence what happens to their children.",
      body: [
        {"t":"p","x":"Districts measure family engagement the way it is easiest to measure: how many people came. It is a real number and a poor proxy. A packed cafeteria and a community that feels unheard can coexist comfortably."},
        {"t":"h2","x":"Measure influence, not attendance"},
        {"t":"p","x":"The better question is whether a family's input has ever changed a district decision, and whether anyone told them it did. Most families can answer that question instantly, and the answer is usually no."},
        {"t":"quote","x":"We asked four hundred families for feedback and never once published what we did with it. That is not engagement, that is data collection.","cite":"Director of community partnerships"},
        {"t":"ul","items":["Name one decision per year that families will genuinely shape — and say so in advance.","Publish what you heard, including the parts you are not acting on and why.","Translate everything that matters, not everything that is required.","Meet where families already are before asking them to come where you are."]},
        {"t":"callout","label":"A low-cost starting point","x":"Close the loop on last year's survey. A one-page 'here is what you told us and what changed' does more for trust than a new survey will."}
      ]
    },
    {
      slug: "three-priorities-not-thirteen",
      title: "Three priorities, not thirteen",
      category: "Strategic Planning",
      date: "2026-05-26", dateLabel: "May 26, 2026", read: 5,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-1.jpg",
      excerpt: "Narrowing is the hardest hour of the planning process, because every item on the list belongs to somebody who fought to put it there.",
      body: [
        {"t":"p","x":"The list always starts long. Forty items, each defensible, each with a constituency. The instinct is to cluster them into themes until the list looks like three priorities while still containing forty commitments."},
        {"t":"h2","x":"Clustering is not narrowing"},
        {"t":"p","x":"A theme called 'Student Success' that contains eleven initiatives has not reduced anything. It has hidden the problem behind a heading. The organization still has eleven things to resource, and it will resource them unevenly and quietly."},
        {"t":"quote","x":"We had three priorities on the cover and thirty-eight underneath. Nobody was fooled, including us.","cite":"Cabinet member, second planning cycle"},
        {"t":"h2","x":"How to actually cut"},
        {"t":"ol","items":["Force a ranking. No ties, no tiers, no 'equally important.'","Ask what would visibly break if an item were dropped for two years. If nothing, drop it.","Name the items you are stopping, publicly. Silent abandonment costs more trust than an announced end.","Leave capacity unallocated. A plan with no slack cannot absorb the year's surprises."]},
        {"t":"callout","label":"Expect grief","x":"Cutting a priority means telling someone their work is not the district's focus. Do it in person, before the document publishes."}
      ]
    },
    {
      slug: "coaching-teachers-ask-for",
      title: "Coaching that teachers actually ask for",
      category: "Leadership",
      date: "2026-05-12", dateLabel: "May 12, 2026", read: 6,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-2.jpg",
      excerpt: "When coaching is assigned as a consequence, it becomes a signal of deficiency. When it is requested, it becomes the most efficient professional learning a district funds.",
      body: [
        {"t":"p","x":"Ask a teacher what a coach is for and the answer tells you everything about your district's coaching model. If the answer involves the word 'struggling,' the model is remedial, and the strongest teachers will never use it."},
        {"t":"h2","x":"Separate coaching from evaluation completely"},
        {"t":"p","x":"Not mostly. Completely. The moment a coach's observations can appear in an evaluation, the relationship changes and the honest conversation stops. Districts that blur this line get compliant coaching and no growth."},
        {"t":"quote","x":"The first year, coaches were used as extra evaluators. It took two years to undo what that did to trust.","cite":"Principal, partner district"},
        {"t":"h2","x":"What makes coaching requested"},
        {"t":"ul","items":["Coaches carry no evaluative weight, and everyone knows the policy by name.","The strongest teachers are visibly coached first, not last.","Coaching cycles are short and specific — four weeks on one practice, not a year on 'instruction.'","Teachers choose the focus; the coach chooses the method."]},
        {"t":"callout","label":"A signal worth watching","x":"Track the ratio of requested to assigned coaching cycles. If it is not moving toward requested over two years, the model is still read as remedial."}
      ]
    },
    {
      slug: "the-climate-survey-youd-rather-ignore",
      title: "Reading the climate survey you would rather ignore",
      category: "Culture & Community",
      date: "2026-04-28", dateLabel: "April 28, 2026", read: 4,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-3.jpg",
      excerpt: "The item with the worst score is rarely the item that matters most. Look instead for the question where staff and leadership disagree the most.",
      body: [
        {"t":"p","x":"Every climate survey produces one embarrassing number, and districts spend their energy there. Usually it is communication, and usually the response is a newsletter."},
        {"t":"h2","x":"Find the perception gap"},
        {"t":"p","x":"The more useful analysis compares how leadership answered a question with how staff answered the same question. Where the gap is widest, you have found something nobody is saying out loud."},
        {"t":"quote","x":"Ninety-one percent of our administrators said staff felt safe raising concerns. Thirty-four percent of staff agreed. We had been running on the ninety-one.","cite":"Assistant superintendent"},
        {"t":"ol","items":["Rank items by the leadership-to-staff gap, not by raw score.","Share the gap openly with the leadership team before you share it widely.","Pick one gap to close this year. One."]},
        {"t":"callout","label":"Before the next survey","x":"If you cannot point to something that changed because of the last one, response rates will tell you so."}
      ]
    },
    {
      slug: "improvement-plan-meets-budget-calendar",
      title: "When the improvement plan meets the budget calendar",
      category: "Strategic Planning",
      date: "2026-04-14", dateLabel: "April 14, 2026", read: 5,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-1.jpg",
      excerpt: "Plans are written in the spring on an academic calendar and funded in the winter on a fiscal one. Districts that never reconcile the two fund last year's priorities forever.",
      body: [
        {"t":"p","x":"The strategic plan is adopted in June. The budget that would pay for it is built the following January, by a different group, against a different timeline, under different constraints. Nobody designed this. It simply accumulated."},
        {"t":"h2","x":"Put the plan in the budget room"},
        {"t":"p","x":"The fix is procedural and unglamorous: every budget request carries the priority it serves, and requests that serve no priority are labeled as such rather than quietly funded because they always have been."},
        {"t":"ul","items":["Tag every line to a priority — or explicitly to 'operations, no priority.'","Report the percentage of new spending tied to priorities at the board meeting.","Review the tag list in the fall, before requests are written, not after."]},
        {"t":"quote","x":"The first year we tagged the budget, eleven percent of new spending mapped to our priorities. That number was the whole conversation.","cite":"Business administrator, partner district"},
        {"t":"callout","label":"The uncomfortable number","x":"Calculate what share of your discretionary spending advances a stated priority. Most districts are surprised, and the surprise is productive."}
      ]
    },
    {
      slug: "when-the-coach-leaves",
      title: "Designing partnerships you can end",
      category: "Leadership",
      date: "2026-03-31", dateLabel: "March 31, 2026", read: 5,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-2.jpg",
      excerpt: "The measure of external support is not what improves while the consultants are in the building. It is what still runs eighteen months after they leave.",
      body: [
        {"t":"p","x":"Any competent partner can make things look better while they are present. Meetings run on time, data gets pulled, the protocol is followed — because someone whose job it is to care about those things is in the room."},
        {"t":"h2","x":"Build the rhythm, not the dependency"},
        {"t":"p","x":"Good partnership design starts from the exit. Who will run this meeting when we are gone? Who pulls the data? Who notices when the rhythm slips, and what do they do about it?"},
        {"t":"quote","x":"Two years in, the district runs the quarterly review without us. That is the outcome we design for.","cite":"Focused Schools partnership lead"},
        {"t":"ol","items":["Name the internal owner for every practice on day one, not at the end.","Have the internal owner run the meeting by the third cycle, with the partner in the room.","Leave the artifacts — agendas, protocols, slide templates — in the district's own drive.","Schedule one check-in after the engagement formally ends."]},
        {"t":"callout","label":"A fair question to ask any partner","x":"'What will still be running here in two years if we do not renew?' The answer should be specific."}
      ]
    },
    {
      slug: "the-meeting-that-should-be-a-memo",
      title: "The leadership meeting that should have been a memo",
      category: "Culture & Community",
      date: "2026-03-17", dateLabel: "March 17, 2026", read: 4,
      image: "https://focused-schools-rebrand.vercel.app/assets/img/retreat-3.jpg",
      excerpt: "Cabinet time is the scarcest resource a district has, and most of it is spent on information transfer that could have been read in four minutes.",
      body: [
        {"t":"p","x":"Count the minutes in your last leadership meeting that were spent on updates nobody could act on. In most districts it is more than half."},
        {"t":"h2","x":"Reserve the room for decisions"},
        {"t":"p","x":"Information can be read. Decisions need a room. Sorting agenda items into those two buckets before the meeting is a ten-minute discipline that returns hours."},
        {"t":"ul","items":["Circulate updates in writing twenty-four hours ahead; the meeting assumes they were read.","Every agenda item carries a verb: decide, design, or discuss.","Items with no verb come off the agenda."]},
        {"t":"quote","x":"We cut cabinet from two hours to seventy-five minutes and made more decisions. The updates were never the hard part.","cite":"Superintendent, partner district"},
        {"t":"callout","label":"Watch for the drift","x":"Within a quarter, updates creep back in. Re-sort the agenda every semester."}
      ]
    }
  ];

  /* ------------------------- Partner map: every engagement since 2000 --- */
  var partnerMap = [
    { state: "Canada", lat: 53.6209059, lng: -113.5429892, summary: "1 District",
      current: [],
      previous: ["Edmonton Public Schools"],
      projects: [] },
    { state: "Arizona", lat: 33.7186, lng: -112.3099, summary: "2 Districts",
      current: [],
      previous: ["Rowland Unified School District","Peoria Unified School District"],
      projects: [] },
    { state: "California", lat: 36.778259, lng: -119.417931, summary: "11 Districts",
      current: ["Covina-Valley Unified School District","Downey Unified School District","San Marino Unified School District"],
      previous: ["Alliance College-Ready Public Schools","Lowell Joint School District","Los Angeles County Office of Education","Mammoth Unified School District","Monrovia Public Schools","Stockton Unified School District","Yucaipa-Calimesa Joint USD"],
      projects: [] },
    { state: "Connecticut", lat: 41.6032645, lng: -73.087864, summary: "3 Districts",
      current: ["Capitol Region Education Council","East Windsor Public Schools"],
      previous: ["New Haven Public Schools"],
      projects: [] },
    { state: "Delaware", lat: 39.422675, lng: -75.740239, summary: "1 District",
      current: ["MOT Charter Schools"],
      previous: [],
      projects: [] },
    { state: "Illinois", lat: 40.0796625, lng: -89.4337344, summary: "4 Districts",
      current: ["Champaign Unit 4 School District","Springfield Public School District #186"],
      previous: ["Sangamon-Menard Regional Office of Education","Brown County School District #1","Rantoul City Schools SD 137"],
      projects: [] },
    { state: "Indiana", lat: 40.273502, lng: -86.126976, summary: "1 District",
      current: [],
      previous: ["State of Indiana Department of Education (IDOE)"],
      projects: [] },
    { state: "Kentucky", lat: 37.9404, lng: -85.6435, summary: "1 District",
      current: [],
      previous: ["Bullitt County Public Schools"],
      projects: [] },
    { state: "Massachusetts", lat: 42.407211, lng: -71.382439, summary: "16 Districts",
      current: ["Athol-Royalston Regional School District","Blackstone-Millville Regional School District","Fitchburg Public Schools","Medway Public Schools"],
      previous: ["Hampden-Wilbraham Public Schools","Ludlow Public School District","New Bedford Public Schools","Palmer Public School District","Somerville Public Schools","Southbridge Public Schools","Springfield Public Schools","Watertown Public Schools","Hudson Public Schools","Shrewsbury Public Schools","Worcester Public Schools","Marlborough Public Schools","Mohawk Trail & Hawlemont Regional School Districts","Natick Public Schools"],
      projects: [] },
    { state: "Mississippi", lat: 32.5610555, lng: -91.1982, summary: "1 District",
      current: [],
      previous: ["Mississippi Achievement School District"],
      projects: [] },
    { state: "Missouri", lat: 39.0825, lng: -94.3874, summary: "2 Districts",
      current: [],
      previous: ["Independence School District","Saint Louis Public Schools"],
      projects: [] },
    { state: "New Jersey", lat: 40.3231756, lng: -74.6406927, summary: "1 District",
      current: [],
      previous: ["Mathematica Policy Research"],
      projects: [] },
    { state: "New York", lat: 40.7086765, lng: -74.0126734, summary: "1 Project",
      current: [],
      previous: [],
      projects: ["The Principal's Story"] },
    { state: "Ohio", lat: 41.3918325, lng: -81.6619337, summary: "2 Districts",
      current: [],
      previous: ["Educational Service Center of Northeast Ohio"],
      projects: [] },
    { state: "Oklahoma", lat: 35.0207568, lng: -97.2437105, summary: "2 Districts, 1 University",
      current: [],
      previous: ["University of Oklahoma","Tulsa Public Schools","Oklahoma City Public Schools"],
      projects: [] },
    { state: "Oregon", lat: 43.8041334, lng: -120.5542012, summary: "1 District",
      current: [],
      previous: ["Greater Albany Public Schools"],
      projects: [] },
    { state: "Pennsylvania", lat: 40.4446794, lng: -79.9801456, summary: "1 District",
      current: [],
      previous: ["Pittsburgh Public Schools"],
      projects: [] },
    { state: "South Dakota", lat: 43.5291222, lng: -96.7102757, summary: "1 District",
      current: [],
      previous: ["Sioux Falls School District"],
      projects: [] },
    { state: "Tennessee", lat: 36.1678393, lng: -86.7781606, summary: "1 District",
      current: [],
      previous: ["Metro Nashville Public Schools"],
      projects: [] },
    { state: "Texas", lat: 33.5801105, lng: -101.8866668, summary: "1 ROE",
      current: [],
      previous: ["Region 17 ESC"],
      projects: [] },
    { state: "Vermont", lat: 43.957199, lng: -72.7166041, summary: "1 District",
      current: ["Windham Northeast Supervisory Union"],
      previous: [],
      projects: [] },
    { state: "Virginia", lat: 36.850769, lng: -76.285873, summary: "1 District",
      current: [],
      previous: ["Norfolk Public Schools"],
      projects: [] },
    { state: "Washington", lat: 47.6062095, lng: -122.3320708, summary: "3 Districts",
      current: [],
      previous: ["Seattle Public Schools","Spokane Public Schools","Yakima School District"],
      projects: [] },
    { state: "West Virginia", lat: 38.4192496, lng: -82.445154, summary: "1 District",
      current: [],
      previous: ["Cabell County Public Schools"],
      projects: [] },
    { state: "Wisconsin", lat: 42.5846772, lng: -87.8212264, summary: "3 Districts",
      current: [],
      previous: ["Kenosha Unified School District","Sheboygan Area School District"],
      projects: [] }
  ];

  /* ------------------------------------------------- Partner districts --- */
  var partners = [
    { state: "California", districts: ["Covina-Valley Unified School District", "Downey Unified School District", "San Marino Unified School District"] },
    { state: "Connecticut", districts: ["Capitol Region Education Council", "East Windsor Public Schools"] },
    { state: "Illinois", districts: ["Champaign Unit 4 School District", "Rantoul City Schools SD 137", "Springfield Public School District #18"] },
    { state: "Massachusetts", districts: ["Athol-Royalston", "Fitchburg Public Schools", "Marlborough Public Schools", "Medway Public Schools", "Mohawk Trail & Hawlemont Regional School Districts", "Natick Public Schools"] },
    { state: "Vermont", districts: ["Windham Northeast Supervisory Union"] }
  ];

  /* ------------------------------------- Shared numbers (theme options) --- */
  var stats = [
    { value: "2+", unit: "Million", label: "Students impacted", aria: "More than 2 million students impacted" },
    { value: "20+", unit: "Years", label: "Partnering with schools", aria: "More than 20 years partnering with schools" },
    { value: "25+", unit: "States", label: "Served", aria: "More than 25 states served" }
  ];

  return {
    IMG: IMG,
    stories: stories,
    team: team,
    services: services,
    episodes: episodes,
    videos: videos,
    posts: posts,
    blogCategories: blogCategories,
    blogAuthor: blogAuthor,
    partners: partners,
    partnerMap: partnerMap,
    stats: stats,
    /* One editable promise, referenced everywhere it appears. */
    replyTime: "within two business days",
    phone: "844-957-2466",
    phoneHref: "tel:+18449572466",
    email: "hello@focusedschools.com"
  };
})();
