<?php
/**
 * Candidate intake form — loaded by the ATP Campaign Site plugin.
 *
 * Internal module. Do NOT add a WordPress plugin header here; this
 * file is included by ../../atp-demo-plugin.php via require_once and
 * is not itself a plugin entry point. A stray plugin header on this
 * file caused third-party scanners (SiteGround Security's activity
 * log) to misdetect it as a plugin and emit "Undefined array key
 * 'Name'" warnings during install.
 */
if(!defined('ABSPATH'))exit;

/* ════════════════════════════════════════
   DEFAULT QUESTIONS
════════════════════════════════════════ */
function atp_default_questions(){return[
/* ── Step 0 — Source Check (Gateway) ── */
['id'=>'q0','section'=>'00 — Source Check','question'=>'Let\'s start with your information and data sources','subtitle'=>'Paste your official filing URL and Ballotpedia page if available — we\'ll pre-fill what we can.','fields'=>[
    ['id'=>'filler_name','label'=>'Your name','type'=>'text','placeholder'=>'Full name of person filling out this form','optional'=>false],
    ['id'=>'filler_email','label'=>'Your email','type'=>'email','placeholder'=>'email@campaign.com','optional'=>false],
    ['id'=>'filler_phone','label'=>'Your phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>false],
    ['id'=>'filler_role','label'=>'Your role','type'=>'text','placeholder'=>'Campaign Manager, Treasurer, Candidate...','optional'=>false],
    ['id'=>'filing_url','label'=>'Official campaign filing URL','type'=>'url','placeholder'=>'FEC, state election commission, or county filing URL','optional'=>true],
    ['id'=>'ballotpedia_url','label'=>'Ballotpedia candidate page URL','type'=>'url','placeholder'=>'https://ballotpedia.org/...','optional'=>true],
    ['id'=>'existing_website','label'=>'Existing campaign website','type'=>'url','placeholder'=>'https://','optional'=>true],
]],
/* ── Step 1 — Identity & Race ── */
['id'=>'q1','section'=>'01 — Identity & Race','question'=>'Candidate identity and race details','subtitle'=>'If you provided a filing URL, confirm the pre-filled values below. Otherwise enter all fields manually.','fields'=>[
    ['id'=>'org_type','label'=>'Organization type','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Campaign Committee','PAC','Super PAC','Party Committee','Other']],
    ['id'=>'legal_name','label'=>'Full legal name','type'=>'text','placeholder'=>'e.g. Jonathan R. Stacy','optional'=>false],
    ['id'=>'display_name','label'=>'Public-facing display name','type'=>'text','placeholder'=>'e.g. John Stacy','optional'=>false],
    ['id'=>'ballot_name','label'=>'Name as it appears on ballot','type'=>'text','placeholder'=>'e.g. John R. Stacy','optional'=>false],
    ['id'=>'office','label'=>'Office sought','type'=>'text','placeholder'=>'e.g. County Commissioner','optional'=>false],
    ['id'=>'district','label'=>'District / County / Jurisdiction','type'=>'text','placeholder'=>'e.g. Precinct 4, Rockwall County','optional'=>false],
    ['id'=>'seat_number','label'=>'Seat number','type'=>'text','placeholder'=>'If applicable','optional'=>true],
    ['id'=>'state','label'=>'State','type'=>'text','placeholder'=>'Texas','optional'=>false],
    ['id'=>'party','label'=>'Party','type'=>'text','placeholder'=>'Republican / Democrat / Nonpartisan','optional'=>false],
    ['id'=>'election_year','label'=>'Election year','type'=>'text','placeholder'=>'e.g. 2026','optional'=>false],
    ['id'=>'election_date','label'=>'Election date','type'=>'date','placeholder'=>'','optional'=>false],
    ['id'=>'election_type','label'=>'Election type','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Primary','General','Runoff','Special election']],
    ['id'=>'position','label'=>'Candidate status','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Incumbent','Challenger','Open seat — no incumbent']],
]],
/* ── Step 2 — Campaign Contact ── */
['id'=>'q2','section'=>'02 — Campaign Contact','question'=>'Who do we communicate with?','subtitle'=>'','fields'=>[
    ['id'=>'contact_name','label'=>'Primary contact name','type'=>'text','placeholder'=>'Full name','optional'=>false],
    ['id'=>'contact_role','label'=>'Role','type'=>'text','placeholder'=>'Campaign Manager, Candidate...','optional'=>false],
    ['id'=>'contact_email','label'=>'Email','type'=>'email','placeholder'=>'campaign@email.com','optional'=>false],
    ['id'=>'contact_phone','label'=>'Phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>false],
    ['id'=>'contact_address','label'=>'Campaign mailing address','type'=>'text','placeholder'=>'Campaign mailing address','optional'=>true],
    ['id'=>'manager_name','label'=>'Campaign manager name','type'=>'text','placeholder'=>'If different from primary contact','optional'=>true],
    ['id'=>'manager_email','label'=>'Campaign manager email','type'=>'email','placeholder'=>'manager@email.com','optional'=>true],
    ['id'=>'manager_phone','label'=>'Campaign manager phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>true],
    ['id'=>'treasurer_name','label'=>'Treasurer name','type'=>'text','placeholder'=>'Treasurer full name (for legal disclaimer)','optional'=>false],
    ['id'=>'treasurer_email','label'=>'Treasurer email','type'=>'email','placeholder'=>'treasurer@email.com','optional'=>true],
    ['id'=>'treasurer_phone','label'=>'Treasurer phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>true],
    ['id'=>'treasurer_address','label'=>'Treasurer mailing address','type'=>'text','placeholder'=>'Treasurer address','optional'=>true],
]],
/* ── Step 3 — Bio & Messaging ── */
['id'=>'q3','section'=>'03 — Bio & Messaging','question'=>'Tell us about the candidate','subtitle'=>'','fields'=>[
    ['id'=>'ballotpedia_status','label'=>'Ballotpedia survey status','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Already completed — URL provided in Step 0','Completed via ATP Typeform','I\'ll fill in bio and platform fields below','Not sure what Ballotpedia is'],'enum'=>['completed_ballotpedia','completed_atp_typeform','inline_only','not_yet']],
    ['id'=>'homepage_intro','label'=>'Homepage intro — 1 to 2 sentences for the hero section','type'=>'textarea','placeholder'=>'A short introduction that appears at the top of the homepage...','optional'=>false],
    ['id'=>'bio_full','label'=>'Full bio','type'=>'textarea','placeholder'=>'Full background, career, family, community involvement...','optional'=>true],
    ['id'=>'why_running','label'=>'Why is this candidate running?','type'=>'textarea','placeholder'=>'What motivated this campaign...','optional'=>false],
    ['id'=>'tagline','label'=>'Campaign tagline or slogan','type'=>'text','placeholder'=>'e.g. Proven Leadership. Real Results.','optional'=>true],
    ['id'=>'differentiator','label'=>'What makes this candidate different?','type'=>'textarea','placeholder'=>'1–2 sentences...','optional'=>false],
    ['id'=>'key_messages','label'=>'Three key campaign messages','type'=>'textarea','placeholder'=>"Message 1: ...\nMessage 2: ...\nMessage 3: ...",'optional'=>false],
    ['id'=>'policy_passions','label'=>'Policy passions — issues the candidate cares most about','type'=>'textarea','placeholder'=>'What policy areas drive this candidate...','optional'=>true],
    ['id'=>'endorsements_about','label'=>'Endorsements for About page','type'=>'textarea','placeholder'=>"Names, titles, and quotes — e.g.\nSheriff Mike Brown — 'John has my full support'\nRockwall County Republican Party",'optional'=>true],
]],
/* ── Step 4 — Platform & Issues ── */
['id'=>'q4','section'=>'04 — Platform & Issues','question'=>'Policy positions and issue priorities','subtitle'=>'Select the issue categories that matter most, then describe positions below.','fields'=>[
    ['id'=>'issue_categories','label'=>'Select up to 5 issue areas','type'=>'checkbox','placeholder'=>'','optional'=>false,'max'=>5,'options'=>['Economy & Jobs','Education','Healthcare','Public Safety & Crime','Immigration','Taxes & Government Spending','Infrastructure & Transportation','Environment & Energy','Housing & Development','Veterans & Military','Agriculture & Rural','Election Integrity & Voting','Technology & Privacy','Social Issues','Other']],
    ['id'=>'issue_positions','label'=>'Describe your position on each selected issue','type'=>'textarea','placeholder'=>"For each issue selected above, explain the candidate's stance...\n\nEconomy & Jobs: ...\nPublic Safety: ...",'optional'=>false],
    ['id'=>'opponents_missing_issue','label'=>'Most important issue opponents aren\'t discussing','type'=>'textarea','placeholder'=>'What issue are opponents ignoring?','optional'=>true],
    ['id'=>'changed_position','label'=>'A position that has changed or evolved over time','type'=>'textarea','placeholder'=>'Has the candidate\'s stance on any issue evolved? Describe...','optional'=>true],
]],
/* ── Step 5 — Background & Credentials ── */
['id'=>'q5','section'=>'05 — Background & Credentials','question'=>'Professional background and credentials','subtitle'=>'Optional but strongly recommended — enriches the About page and supports your Ballotpedia profile.','fields'=>[
    ['id'=>'profession','label'=>'Current profession','type'=>'text','placeholder'=>'e.g. Attorney, Small Business Owner, Educator','optional'=>true],
    ['id'=>'current_role','label'=>'Current role / title','type'=>'text','placeholder'=>'e.g. Partner at Smith & Associates','optional'=>true],
    ['id'=>'previous_experience','label'=>'Previous professional experience','type'=>'textarea','placeholder'=>'Key roles, accomplishments, community leadership...','optional'=>true],
    ['id'=>'education_1','label'=>'Education — University 1','type'=>'text','placeholder'=>'University, Degree, Year (e.g. Texas A&M, B.S. Political Science, 2004)','optional'=>true],
    ['id'=>'education_2','label'=>'Education — University 2','type'=>'text','placeholder'=>'University, Degree, Year','optional'=>true],
    ['id'=>'education_3','label'=>'Education — University 3','type'=>'text','placeholder'=>'University, Degree, Year','optional'=>true],
    ['id'=>'military_branch','label'=>'Military service — branch','type'=>'text','placeholder'=>'e.g. U.S. Army, U.S. Marine Corps','optional'=>true],
    ['id'=>'military_years','label'=>'Military service — years','type'=>'text','placeholder'=>'e.g. 2001–2009','optional'=>true],
]],
/* ── Step 6 — Visual Branding ── */
['id'=>'q6','section'=>'06 — Visual Branding','question'=>'Campaign branding and visual assets','subtitle'=>'Share what you have. We use the ATP template for anything missing.','fields'=>[
    ['id'=>'headshot','label'=>'Candidate headshot (required)','type'=>'file','placeholder'=>'JPG, PNG, or HEIC — max 10 MB','optional'=>false,'accept'=>'.jpg,.jpeg,.png,.heic','max_size'=>10,'max_files'=>1],
    ['id'=>'logo','label'=>'Campaign logo','type'=>'file','placeholder'=>'JPG, PNG, SVG, or PDF — max 10 MB','optional'=>true,'accept'=>'.jpg,.jpeg,.png,.svg,.pdf','max_size'=>10,'max_files'=>1],
    ['id'=>'additional_photos','label'=>'Additional campaign photos','type'=>'file','placeholder'=>'JPG, PNG, or HEIC — max 10 MB each, up to 10 files','optional'=>true,'accept'=>'.jpg,.jpeg,.png,.heic','max_size'=>10,'max_files'=>10],
    ['id'=>'color_primary','label'=>'Primary brand color','type'=>'text','placeholder'=>'e.g. Navy #002868 — or leave blank for template','optional'=>true],
    ['id'=>'color_secondary','label'=>'Secondary brand color','type'=>'text','placeholder'=>'e.g. Red #BF0A30','optional'=>true],
    ['id'=>'color_accent','label'=>'Accent color','type'=>'text','placeholder'=>'e.g. Gold #C4A84F','optional'=>true],
    ['id'=>'visual_style','label'=>'Visual style preference','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Professional / polished','Bold / aggressive','Grassroots / approachable','Clean / minimal','Traditional / patriotic','Modern / tech-forward','No preference — use template']],
    ['id'=>'design_notes','label'=>'Design notes','type'=>'textarea','placeholder'=>'Any additional notes about branding, look and feel, or existing materials...','optional'=>true],
]],
/* ── Step 7 — Social Media ── */
['id'=>'q7','section'=>'07 — Social Media','question'=>'Campaign social profiles','subtitle'=>'Share only what exists. Leave blank if not applicable.','fields'=>[
    ['id'=>'facebook','label'=>'Facebook','type'=>'url','placeholder'=>'https://facebook.com/...','optional'=>true],
    ['id'=>'twitter_x','label'=>'X / Twitter','type'=>'url','placeholder'=>'https://x.com/...','optional'=>true],
    ['id'=>'instagram','label'=>'Instagram','type'=>'url','placeholder'=>'https://instagram.com/...','optional'=>true],
    ['id'=>'youtube','label'=>'YouTube','type'=>'url','placeholder'=>'https://youtube.com/...','optional'=>true],
    ['id'=>'tiktok','label'=>'TikTok','type'=>'url','placeholder'=>'https://tiktok.com/...','optional'=>true],
    ['id'=>'linkedin','label'=>'LinkedIn','type'=>'url','placeholder'=>'https://linkedin.com/...','optional'=>true],
    ['id'=>'social_other','label'=>'Other platform','type'=>'url','placeholder'=>'Any other platform URL','optional'=>true],
]],
/* ── Step 8 — Video ── */
['id'=>'q8','section'=>'08 — Video','question'=>'Do you have video content?','subtitle'=>'Optional — can be added after launch.','fields'=>[
    ['id'=>'video_main','label'=>'Main campaign video (YouTube / Vimeo URL)','type'=>'url','placeholder'=>'https://youtube.com/...','optional'=>true],
    ['id'=>'video_other','label'=>'Other video assets','type'=>'textarea','placeholder'=>'Ads, testimonials, event clips. Include links.','optional'=>true],
]],
/* ── Step 9 — Survey Page ── */
['id'=>'q9','section'=>'09 — Survey Page','question'=>'Set up your website survey','subtitle'=>'This adds a voter engagement survey to your campaign site. ATP handles survey design and delivery.','fields'=>[
    ['id'=>'survey_page_wanted','label'=>'Include a survey page on your website?','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Yes — include a survey page','No — skip for now']],
    ['id'=>'primary_survey_focus','label'=>'Primary survey focus','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Overall candidate impression','Issue priorities & positions','Messaging & communications feedback','Likelihood to vote & support','Volunteer & grassroots feedback','Website & digital experience'],'cond'=>'cond-survey','helper'=>'<strong>Overall candidate impression</strong> — Tracks favorability, trust, and support intensity<br><strong>Issue priorities &amp; positions</strong> — What to emphasize in messaging and which policies to feature<br><strong>Messaging &amp; communications feedback</strong> — Guides spend and channel mix; tests slogans before scaling<br><strong>Likelihood to vote &amp; support</strong> — Segment into supporters, persuadables, and GOTV targets<br><strong>Volunteer &amp; grassroots feedback</strong> — Improves events and field operations<br><strong>Website &amp; digital experience</strong> — Improves site clarity and conversion paths'],
    ['id'=>'survey_page_label','label'=>'What should the page be called?','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Survey','Share Feedback','Voter Priorities','Community Input','Custom (enter below)'],'cond'=>'cond-survey'],
    ['id'=>'survey_page_label_custom','label'=>'Custom page label','type'=>'text','placeholder'=>'Enter custom page name...','optional'=>true,'cond'=>'cond-survey'],
    ['id'=>'survey_display','label'=>'Survey placement','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Dedicated page only','Homepage section only','Both dedicated page and homepage section'],'cond'=>'cond-survey'],
    ['id'=>'survey_intro_text','label'=>'Survey page intro text','type'=>'textarea','placeholder'=>'Brief intro that appears above the survey on your website...','optional'=>true,'cond'=>'cond-survey'],
    ['id'=>'existing_survey_link','label'=>'Already have a survey?','type'=>'url','placeholder'=>'Paste link or embed code if you already have one — otherwise leave blank','optional'=>true,'cond'=>'cond-survey'],
]],
/* ── Step 10 — Legal & Compliance ── */
['id'=>'q10','section'=>'10 — Legal & Compliance','question'=>'Legal details and compliance information','subtitle'=>'These fields generate your Privacy Policy, Cookie-Tracking-SMS Compliance Policy, and paid-for-by disclaimer automatically.','fields'=>[
    ['id'=>'committee_name','label'=>'Committee name','type'=>'text','placeholder'=>'e.g. John Stacy for Commissioner','optional'=>false],
    ['id'=>'paidfor_text','label'=>'Paid-for-by disclaimer text','type'=>'text','placeholder'=>'e.g. Paid for by John Stacy for Commissioner','optional'=>false],
    ['id'=>'jurisdiction','label'=>'Jurisdiction','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Federal (FEC)','State','County','Municipal','Special District']],
    ['id'=>'committee_id','label'=>'Committee / campaign finance ID','type'=>'text','placeholder'=>'FEC ID or state campaign finance ID','optional'=>true],
    ['id'=>'committee_address','label'=>'Committee mailing address','type'=>'text','placeholder'=>'Official committee address','optional'=>false],
    ['id'=>'campaign_phone','label'=>'Campaign phone (for legal pages)','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>false],
    ['id'=>'campaign_email','label'=>'Campaign email (for legal pages)','type'=>'email','placeholder'=>'info@campaign.com','optional'=>true],
    ['id'=>'privacy_contact_email','label'=>'Privacy policy contact email','type'=>'email','placeholder'=>'privacy@campaign.com — often different from campaign email','optional'=>false],
    ['id'=>'privacy_contact_phone','label'=>'Privacy policy contact phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>true],
    ['id'=>'privacy_contact_address','label'=>'Privacy policy contact address','type'=>'text','placeholder'=>'Address for privacy-related inquiries','optional'=>false],
    ['id'=>'uses_cookies','label'=>'Does the campaign use cookies or analytics tracking?','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Yes','No','Not sure yet']],
    ['id'=>'will_send_texts','label'=>'Will this campaign send political text messages?','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Yes','No','Not sure yet']],
    ['id'=>'sms_categories','label'=>'SMS messaging categories','type'=>'checkbox','placeholder'=>'','optional'=>true,'options'=>['Campaign updates','Fundraising','Event reminders','Survey follow-ups','Get-out-the-vote','Volunteer recruitment','Polling','Other'],'enum'=>['campaign_updates','fundraising','event_reminders','survey_followups','gotv','volunteer_recruitment','polling','other']],
    ['id'=>'sms_optin_language','label'=>'SMS opt-in checkbox language','type'=>'text','placeholder'=>'e.g. I agree to receive text updates from this campaign','optional'=>true],
    ['id'=>'survey_follow_up','label'=>'Does the survey collect email or phone for follow-up?','type'=>'radio','placeholder'=>'','optional'=>true,'options'=>['Yes','No','Not sure']],
    ['id'=>'donations_by_text','label'=>'Will donations be solicited by text message?','type'=>'radio','placeholder'=>'','optional'=>true,'options'=>['Yes','No','Not sure']],
    ['id'=>'third_party_analytics','label'=>'Does the campaign use third-party analytics tools (e.g. Google Analytics)?','type'=>'radio','placeholder'=>'','optional'=>true,'options'=>['Yes','No','Not sure']],
    ['id'=>'shares_data','label'=>'Does the campaign share data with third-party vendors?','type'=>'radio','placeholder'=>'','optional'=>true,'options'=>['Yes','No','Not sure']],
    ['id'=>'service_providers','label'=>'Service provider names (for privacy policy)','type'=>'textarea','placeholder'=>'List any vendors who will access campaign data (e.g. ATP, Anedot, Mailchimp)...','optional'=>true],
]],
/* ── Step 11 — Fundraising ── */
['id'=>'q11','section'=>'11 — Fundraising','question'=>'Donation and fundraising setup','subtitle'=>'','fields'=>[
    ['id'=>'donation_needed','label'=>'Do you need a donation page?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — build one','No','Already have one — just link it']],
    ['id'=>'donation_platform','label'=>'Fundraising platform','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['ActBlue','WinRed','Anedot','Revv','NGP VAN','PayPal','Other','Haven\'t chosen yet','Not using one']],
    ['id'=>'fundraising_platform_status','label'=>'Fundraising platform status','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Live and accepting donations','Set up but not yet live','In progress — EIN and bank account established','Haven\'t started yet']],
    ['id'=>'donation_url','label'=>'Donation page URL','type'=>'url','placeholder'=>'e.g. https://secure.anedot.com/campaign/donate','optional'=>true],
    ['id'=>'donation_embed_code','label'=>'Fundraising page embed code','type'=>'textarea','placeholder'=>'Paste the embed code from your fundraising platform if available','optional'=>true],
    ['id'=>'donation_button_label','label'=>'Donation button label','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Donate','Contribute','Chip In','Support the Campaign','Custom (enter below)']],
    ['id'=>'donation_button_custom','label'=>'Custom donation button label','type'=>'text','placeholder'=>'Enter your preferred button text','optional'=>true,'cond'=>'cond-donation-custom'],
    ['id'=>'accept_text_donations','label'=>'Will you accept donations by text message?','type'=>'radio','placeholder'=>'','optional'=>true,'options'=>['Yes','No']],
    ['id'=>'text_donation_processor','label'=>'Text-to-donate processor','type'=>'text','placeholder'=>'If accepting donations by text, which processor?','optional'=>true,'cond'=>'cond-text-donate'],
    ['id'=>'text_donation_accreditation','label'=>'Text-to-donate accreditation','type'=>'text','placeholder'=>'Accreditation or compliance info for text donations','optional'=>true,'cond'=>'cond-text-donate'],
]],
/* ── Step 12 — Domain Setup ── */
['id'=>'q12','section'=>'12 — Domain Setup','question'=>'Domain setup','subtitle'=>'Your Standard website includes: Home, About, Issues, Sign-Up, Donate, Contact, Privacy Policy, Cookie-Tracking-SMS Compliance Policy, and Survey (if selected).','fields'=>[
    ['id'=>'domain_status','label'=>'Do you have a domain?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — I own it and will manage DNS myself','Yes — I own it and want ATP to manage it','I have an existing website to replace or rebuild','No — I need a domain purchased','Not sure']],
    ['id'=>'domain_preferred','label'=>'Preferred domain name','type'=>'text','placeholder'=>'e.g. johnstacyforcommissioner.com','optional'=>true],
    ['id'=>'domain_primary','label'=>'Primary domain (if different from preferred)','type'=>'text','placeholder'=>'The main domain that will be used','optional'=>true],
    ['id'=>'domain_redirects','label'=>'Redirect domains','type'=>'text','placeholder'=>'Other domains that should redirect to the primary site','optional'=>true],
    ['id'=>'domain_registrar','label'=>'Domain registrar','type'=>'text','placeholder'=>'e.g. GoDaddy, Namecheap, Google Domains','optional'=>true],
    ['id'=>'hosting_provider','label'=>'Current hosting provider','type'=>'text','placeholder'=>'e.g. SiteGround, Wix, Squarespace, GoDaddy','optional'=>true,'cond'=>'cond-rebuild'],
    ['id'=>'domain_credentials','label'=>'Do you have login credentials to share?','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['ATP will manage','Candidate will manage','Needs transfer','Not yet registered'],'enum'=>['atp_will_manage','candidate_will_manage','needs_transfer','not_yet_registered'],'cond'=>'cond-rebuild'],
    ['id'=>'campaign_email_needed','label'=>'Do you need a campaign email address on this domain?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — set one up for me','Already have one (e.g., Google Workspace)','No']],
]],
/* ── Step 13 — Approval & Timeline ── */
['id'=>'q13','section'=>'13 — Approval & Timeline','question'=>'Final details and launch timeline','subtitle'=>'','fields'=>[
    ['id'=>'approver_same','label'=>'Is the content approver the same as the primary contact?','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Yes','No']],
    ['id'=>'approver_name','label'=>'Content approver name','type'=>'text','placeholder'=>'Who provides final content approval?','optional'=>false],
    ['id'=>'approver_email','label'=>'Content approver email','type'=>'email','placeholder'=>'approver@email.com','optional'=>false],
    ['id'=>'copy_help','label'=>'Will you provide copy or need writing help?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['We will provide all copy','We need help writing some copy','We need full content writing assistance']],
    ['id'=>'launch_timeline','label'=>'Target launch timeline','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['ASAP','2–4 weeks','1–3 months','Planning ahead — no rush']],
    ['id'=>'launch_date','label'=>'Target launch date','type'=>'date','placeholder'=>'','optional'=>true],
    ['id'=>'comm_pref','label'=>'Preferred communication method','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Email','Phone','Text','All of the above']],
    ['id'=>'referral_source','label'=>'How did you hear about ATP?','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Referral from another campaign or consultant','Web search (Google, Bing, etc.)','Social media (Facebook, X, LinkedIn, etc.)','Event or conference','Ballotpedia','News article or press coverage','Party committee or PAC recommendation','Other']],
    ['id'=>'referral_source_other','label'=>'How did you hear about ATP? (specify)','type'=>'text','placeholder'=>'Please describe...','optional'=>true,'cond'=>'cond-referral-other'],
    ['id'=>'open_notes','label'=>'Anything else we should know?','type'=>'textarea','placeholder'=>'Final notes, questions, context...','optional'=>true],
]],
/* ── Step 14 — Grow Beyond Your Website ── */
['id'=>'q14','section'=>'14 — Grow Beyond Your Website','question'=>'What else does your campaign need?','subtitle'=>'These are not included in your Standard website package. Check anything you\'d like to learn more about — your ATP team will follow up separately. No commitment required.','fields'=>[
    ['id'=>'additional_services','label'=>'Campaign Services','type'=>'checkbox','placeholder'=>'Which of these services are you interested in learning more about?','optional'=>true,'options'=>['Voter file / contact list acquisition','Political text messaging (MMS/SMS)','Polling & survey design','Digital advertising (Google, YouTube)','Social media advertising (Meta/Facebook, Instagram)','Email marketing & mass email','Google Workspace (campaign email, calendar, Drive)','Google Drive campaign asset storage','HubSpot CRM setup','Ballotpedia profile setup','Knowledge graph / discoverability package'],'descs'=>['I need cell phone numbers and voter data for my district','I want to reach voters directly by text message','I want to measure voter sentiment beyond the website survey','I want to run online search and video ads','I want to run paid social media campaigns','I want to build and email a supporter list','Professional email on my campaign domain with calendar, mobile access, shared drives, and team collaboration','Centralized cloud storage for campaign photos, documents, and digital assets','I want to manage contacts, outreach, and pipeline in one place','I want help completing my Ballotpedia Candidate Connection survey','I want to show up when voters Google my name']],
    ['id'=>'tier2_pages','label'=>'Additional Website Pages','type'=>'checkbox','placeholder'=>'Interested in expanding your website beyond the Standard pages?','optional'=>true,'options'=>['Media / Press Kit','Endorsements (dedicated page)','Events / Appearances Calendar','Press / News / Blog','Polling Locator','FAQ'],'descs'=>['Professional media page with press releases, news coverage, and downloadable campaign assets','A standalone page showcasing endorsements with linked sources','A calendar page for rallies, town halls, fundraisers, and public appearances','A news feed or blog for campaign updates, press releases, and announcements','Help voters find their polling place — embedded lookup tool on your site','Frequently asked questions page addressing common voter inquiries'],'subheaders'=>[0=>'Media & Endorsements',2=>'Additional Pages']],
    ['id'=>'additional_survey_focuses','label'=>'Additional Survey Focuses','type'=>'checkbox','placeholder'=>'Want deeper voter insight? Add a survey focused on a different dimension.','optional'=>true,'options'=>['Overall candidate impression','Issue priorities & positions','Messaging & communications feedback','Likelihood to vote & support','Volunteer & grassroots feedback','Website & digital experience'],'descs'=>['Tracks favorability, trust, and support intensity','Tells you what to emphasize in messaging and which policies to feature','Guides spend and channel mix; tests slogans and creative before scaling','Helps segment into supporters, persuadables, and targets for GOTV','Improves events and field operations; surfaces friction for activists','Directly improves the site clarity and conversion paths'],'cond'=>'cond-survey-focuses'],
]],
/* ── Step 15 — Summary & Acknowledgment ── */
['id'=>'q15','section'=>'15 — Summary & Acknowledgment','question'=>'Review and submit','subtitle'=>'Review the details below, confirm your package, and generate your candidate profile.','fields'=>[
    ['id'=>'scope_acknowledgment','label'=>'I understand the above represents my Standard website package. Additional pages and services will be discussed separately.','type'=>'checkbox','placeholder'=>'','optional'=>false,'options'=>['I acknowledge']],
    ['id'=>'compliance_acknowledgment','label'=>'I have reviewed the compliance guide for my filing jurisdiction.','type'=>'checkbox','placeholder'=>'','optional'=>false,'options'=>['I acknowledge']],
]],
];}

function atp_get_settings(){
    return wp_parse_args(get_option('atp_settings',[]),[
        'logo_text'=>'America <span>Tracking</span> Polls',
        'logo_image'=>'',
        'notify_emails'=>['alfonso@mirrorfactory.com','gary@americatrackingpolls.com','dan@americatrackingpolls.com'],
        'notify_subject'=>'New ATP Intake: {candidate_name}',
        'questions'=>atp_default_questions(),
    ]);
}

/* ════════════════════════════════════════
   CPT
════════════════════════════════════════ */
add_action('init','atp_register_cpt');
function atp_register_cpt(){
    register_post_type('atp_candidate',['labels'=>['name'=>'ATP Candidates','singular_name'=>'Candidate'],'public'=>false,'show_ui'=>true,'show_in_menu'=>false,'supports'=>['title'],'capability_type'=>'post']);
}

/* ════════════════════════════════════════
   ADMIN MENU
════════════════════════════════════════ */
add_action('admin_menu','atp_admin_menu');
function atp_admin_menu(){
    add_menu_page('ATP Candidates','ATP Candidates','manage_options','atp-candidates','atp_admin_list','dashicons-groups',30);
    add_submenu_page('atp-candidates','Settings','Settings','manage_options','atp-settings','atp_admin_settings');
}

/* ════════════════════════════════════════
   SUBMISSIONS LIST
════════════════════════════════════════ */
function atp_admin_list(){
    if(isset($_GET['view'])){atp_admin_single((int)$_GET['view']);return;}
    $posts=get_posts(['post_type'=>'atp_candidate','posts_per_page'=>-1,'post_status'=>'publish']);
    $exp_all=wp_nonce_url(admin_url('admin-ajax.php?action=atp_export_all'),'atp_export','nonce');
    echo '<div class="wrap"><h1 style="display:flex;align-items:center;gap:12px"><span style="background:#d42b2b;color:#fff;padding:4px 10px;border-radius:4px;font-size:13px">ATP</span> Candidate Submissions <a href="'.esc_url($exp_all).'" class="button" style="margin-left:auto">Export All JSON</a></h1>';
    if(empty($posts)){echo '<p style="margin-top:24px;color:#666">No submissions yet. Add <code>[atp_intake]</code> to any page.</p></div>';return;}
    echo '<table class="wp-list-table widefat fixed striped" style="margin-top:16px"><thead><tr><th>Candidate</th><th>Office</th><th>State</th><th>Party</th><th>Tier</th><th>Date</th><th>Actions</th></tr></thead><tbody>';
    foreach($posts as $p){
        $m=get_post_meta($p->ID);
        $name=$m['display_name'][0]??$m['legal_name'][0]??$p->post_title;
        $view=admin_url('admin.php?page=atp-candidates&view='.$p->ID);
        $exp=wp_nonce_url(admin_url('admin-ajax.php?action=atp_export_single&id='.$p->ID),'atp_export','nonce');
        $del=get_delete_post_link($p->ID);
        echo '<tr><td><strong>'.esc_html($name).'</strong></td><td>'.esc_html($m['office'][0]??'—').'</td><td>'.esc_html($m['state'][0]??'—').'</td><td>'.esc_html($m['party'][0]??'—').'</td><td>'.esc_html($m['survey_tier'][0]??'—').'</td><td>'.get_the_date('M j, Y',$p).'</td><td><a href="'.esc_url($view).'">View</a> | <a href="'.esc_url($exp).'">JSON</a> | <a href="'.esc_url($del).'" onclick="return confirm(\'Delete?\')" style="color:#b00">Delete</a></td></tr>';
    }
    echo '</tbody></table></div>';
}

/* ════════════════════════════════════════
   SINGLE VIEW
════════════════════════════════════════ */
function atp_admin_single($id){
    $post=get_post($id);if(!$post||$post->post_type!=='atp_candidate'){echo '<div class="wrap"><p>Not found.</p></div>';return;}
    $retry_notice=get_transient('atp_drive_retry_notice_'.get_current_user_id());
    if($retry_notice)delete_transient('atp_drive_retry_notice_'.get_current_user_id());
    $raw=get_post_meta($id);$data=[];foreach($raw as $k=>$v){$data[$k]=maybe_unserialize($v[0]);}
    $name=$data['display_name']??$data['legal_name']??'Candidate';
    $back=admin_url('admin.php?page=atp-candidates');
    $exp=wp_nonce_url(admin_url('admin-ajax.php?action=atp_export_single&id='.$id),'atp_export','nonce');
    $bundle=wp_nonce_url(admin_url('admin-ajax.php?action=atp_export_bundle&id='.$id),'atp_export','nonce');
    $groups=['Source & Gateway'=>['filler_name','filler_email','filler_phone','filler_role','filing_url','ballotpedia_url','existing_website'],'Identity & Race'=>['org_type','legal_name','display_name','ballot_name','office','district','seat_number','state','party','election_year','election_date','election_type','position'],'Campaign Contact'=>['contact_name','contact_role','contact_email','contact_phone','contact_address','manager_name','manager_email','manager_phone','treasurer_name','treasurer_email','treasurer_phone','treasurer_address'],'Bio & Messaging'=>['ballotpedia_status','homepage_intro','bio_full','why_running','tagline','differentiator','key_messages','policy_passions','endorsements_about'],'Platform & Issues'=>['issue_categories','issue_positions','opponents_missing_issue','changed_position'],'Background & Credentials'=>['profession','current_role','previous_experience','education_1','education_2','education_3','military_branch','military_years'],'Visual Branding'=>['headshot','logo','additional_photos','color_primary','color_secondary','color_accent','visual_style','design_notes'],'Social Media'=>['facebook','twitter_x','instagram','youtube','tiktok','linkedin','social_other'],'Video'=>['video_main','video_other'],'Survey Page'=>['survey_page_wanted','primary_survey_focus','survey_page_label','survey_page_label_custom','survey_display','survey_intro_text','existing_survey_link'],'Legal & Compliance'=>['committee_name','paidfor_text','jurisdiction','committee_id','committee_address','campaign_phone','campaign_email','privacy_contact_email','privacy_contact_phone','privacy_contact_address','uses_cookies','will_send_texts','sms_categories','sms_optin_language','survey_follow_up','donations_by_text','third_party_analytics','shares_data','service_providers'],'Fundraising'=>['donation_needed','donation_platform','fundraising_platform_status','donation_url','donation_embed_code','donation_button_label','donation_button_custom','accept_text_donations','text_donation_processor','text_donation_accreditation'],'Domain Setup'=>['domain_status','domain_preferred','domain_primary','domain_redirects','domain_registrar','hosting_provider','domain_credentials','campaign_email_needed'],'Approval & Timeline'=>['approver_same','approver_name','approver_email','copy_help','launch_timeline','launch_date','comm_pref','referral_source','referral_source_other','open_notes'],'Grow Beyond Your Website'=>['additional_services','tier2_pages','additional_survey_focuses'],'Summary'=>['scope_acknowledgment','compliance_acknowledgment']];
    $v3=get_post_meta($id,'_v3_json',true);$v3_data=$v3?json_decode($v3,true):[];
    $sub_folder=get_post_meta($id,'_atp_drive_subfolder_url',true)?:($v3_data['visual_branding']['submission_folder']??'');
    $drive_files_json=get_post_meta($id,'_atp_drive_files',true);
    $drive_files=$drive_files_json?json_decode($drive_files_json,true):[];
    $retry=wp_nonce_url(admin_url('admin-post.php?action=atp_retry_drive_mirror&id='.$id),'atp_retry_drive_mirror_'.$id,'nonce');
    echo '<div class="wrap"><h1>'.esc_html($name).' <a href="'.esc_url($back).'" class="button" style="margin-left:16px;font-size:12px">← All</a> <a href="'.esc_url($bundle).'" class="button button-primary" style="margin-left:8px">⬇ Download Intake Bundle (zip)</a> <a href="'.esc_url($exp).'" class="button" style="margin-left:8px">Download JSON only</a></h1>';
    if($retry_notice){
        $cls=$retry_notice['status']==='drive_retry_success'?'notice-success':'notice-error';
        echo '<div class="notice '.esc_attr($cls).' is-dismissible"><p>'.esc_html($retry_notice['msg']).'</p></div>';
    }
    echo '<div style="background:#fff;border:1px solid #ddd;border-radius:6px;padding:14px 16px;margin:12px 0 18px;max-width:900px">';
    echo '<strong>Google Drive mirror</strong><br>';
    if($sub_folder){echo '<a href="'.esc_url($sub_folder).'" target="_blank" class="button" style="margin:8px 8px 0 0">📁 Open submission folder in Drive</a>';}
    echo '<a href="'.esc_url($retry).'" class="button" style="margin:8px 0 0" onclick="return confirm(\'Retry Drive mirroring for this submission? This may create or update files in the selected Drive folder.\')">Retry Drive mirror</a>';
    if(!empty($drive_files)&&is_array($drive_files)){
        echo '<ul style="margin:10px 0 0 18px">';
        foreach($drive_files as $df){
            $label=esc_html(($df['field']??'file').': '.($df['name']??($df['id']??'')));
            $url=$df['url']??'';
            echo '<li>'.($url?'<a href="'.esc_url($url).'" target="_blank">'.$label.'</a>':$label).'</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="margin:8px 0 0;color:#666">No Drive file metadata stored yet. Use retry after Drive is connected and a destination folder is selected.</p>';
    }
    echo '</div>';
    $img_fields=['headshot','logo','additional_photos'];
    foreach($groups as $g=>$fields){
        $rows='';foreach($fields as $f){
            $v=$data[$f]??null;if(!$v)continue;$l=ucwords(str_replace('_',' ',$f));
            if(is_array($v)){
                $items=[];foreach($v as $vi){
                    if(filter_var($vi,FILTER_VALIDATE_URL)){
                        $is_img=preg_match('/\.(jpg|jpeg|png|gif|heic|webp)$/i',$vi);
                        $items[]=$is_img?'<a href="'.esc_url($vi).'" target="_blank"><img src="'.esc_url($vi).'" style="width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ddd;margin:2px"></a>':'<a href="'.esc_url($vi).'" target="_blank">'.esc_html(basename($vi)).'</a>';
                    } else {$items[]=esc_html($vi);}
                }
                $rows.='<tr><th style="width:200px;text-align:left;padding:8px 12px;color:#666;font-weight:500">'.esc_html($l).'</th><td style="padding:8px 12px">'.implode(' ',$items).'</td></tr>';
            } else {
                $display='';
                if(in_array($f,$img_fields)&&filter_var($v,FILTER_VALIDATE_URL)){
                    $display='<a href="'.esc_url($v).'" target="_blank"><img src="'.esc_url($v).'" style="max-width:120px;max-height:120px;border-radius:4px;border:1px solid #ddd"></a>';
                } elseif(filter_var($v,FILTER_VALIDATE_URL)){
                    $display='<a href="'.esc_url($v).'" target="_blank">'.esc_html($v).'</a>';
                } else {$display=nl2br(esc_html($v));}
                $rows.='<tr><th style="width:200px;text-align:left;padding:8px 12px;color:#666;font-weight:500">'.esc_html($l).'</th><td style="padding:8px 12px">'.$display.'</td></tr>';
            }
        }
        if(!$rows)continue;
        echo '<h2 style="margin-top:24px;font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:#d42b2b;border-bottom:2px solid #d42b2b;padding-bottom:6px">'.esc_html($g).'</h2><table class="widefat"><tbody>'.$rows.'</tbody></table>';
    }
    echo '<h2 style="margin-top:24px;font-size:13px;text-transform:uppercase;color:#d42b2b;border-bottom:2px solid #d42b2b;padding-bottom:6px">Raw JSON</h2>';
    echo '<textarea readonly style="width:100%;height:300px;font-family:monospace;font-size:12px;background:#0e1235;color:#7eb8f7;padding:16px;border:1px solid #ddd;border-radius:4px">'.esc_textarea(json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)).'</textarea></div>';
}

/* ════════════════════════════════════════
   SETTINGS PAGE
════════════════════════════════════════ */
function atp_admin_settings(){
    $s=atp_get_settings();$tab=$_GET['tab']??'questions';
    if(isset($_GET['saved']))echo '<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>';
    ?>
    <div class="wrap">
    <h1 style="display:flex;align-items:center;gap:10px"><span style="background:#d42b2b;color:#fff;padding:4px 10px;border-radius:4px;font-size:13px">ATP</span> Form Settings</h1>
    <nav class="nav-tab-wrapper" style="margin-bottom:20px">
      <a href="?page=atp-settings&tab=questions" class="nav-tab <?=$tab==='questions'?'nav-tab-active':''?>">Questions</a>
      <a href="?page=atp-settings&tab=branding"  class="nav-tab <?=$tab==='branding'?'nav-tab-active':''?>">Branding</a>
      <a href="?page=atp-settings&tab=notifications" class="nav-tab <?=$tab==='notifications'?'nav-tab-active':''?>">Notifications</a>
    </nav>

    <?php if($tab==='questions'):?>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
      <p style="color:#666;margin:0">Edit, add, or delete steps and fields. Changes auto-save.</p>
      <button class="button button-primary" onclick="atpAddStep()">+ Add Step</button>
    </div>
    <p id="atp-save-msg" style="color:#0a7;font-weight:600;min-height:20px"></p>
    <div id="atp-q-list">
    <?php foreach($s['questions'] as $qi=>$q):?>
    <div class="atp-sc" data-id="<?=esc_attr($q['id'])?>" style="background:#fff;border:1px solid #ddd;border-radius:6px;margin-bottom:10px">
      <div style="display:flex;align-items:center;gap:10px;padding:11px 16px;background:#f9f9f9;border-bottom:1px solid #eee;cursor:pointer" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
        <span style="background:#d42b2b;color:#fff;padding:2px 8px;border-radius:3px;font-size:11px;font-weight:700"><?=$qi+1?></span>
        <strong style="flex:1"><?=esc_html($q['section'])?></strong>
        <small style="color:#999"><?=count($q['fields'])?> field<?=count($q['fields'])!==1?'s':''?></small>
        <button class="button button-small" style="color:#b00;border-color:#b00" onclick="event.stopPropagation();atpDelStep('<?=esc_attr($q['id'])?>')">Delete step</button>
      </div>
      <div style="display:none;padding:16px">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:12px">
          <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;color:#666;display:block;margin-bottom:4px">Section Label</label><input type="text" value="<?=esc_attr($q['section'])?>" onchange="atpUpdStep('<?=esc_attr($q['id'])?>','section',this.value)" style="width:100%"></div>
          <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;color:#666;display:block;margin-bottom:4px">Question</label><input type="text" value="<?=esc_attr($q['question'])?>" onchange="atpUpdStep('<?=esc_attr($q['id'])?>','question',this.value)" style="width:100%"></div>
          <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;color:#666;display:block;margin-bottom:4px">Subtitle</label><input type="text" value="<?=esc_attr($q['subtitle']??'')?>" onchange="atpUpdStep('<?=esc_attr($q['id'])?>','subtitle',this.value)" style="width:100%" placeholder="Optional"></div>
        </div>
        <label style="font-size:11px;font-weight:700;text-transform:uppercase;color:#666;display:block;margin-bottom:8px">Fields</label>
        <table class="widefat" style="margin-bottom:10px">
          <thead><tr><th style="width:110px">ID</th><th>Label</th><th style="width:100px">Type</th><th>Placeholder</th><th style="width:60px">Optional</th><th style="width:40px"></th></tr></thead>
          <tbody>
          <?php foreach($q['fields'] as $f):?>
          <tr>
            <td><input type="text" value="<?=esc_attr($f['id'])?>" style="width:100%;font-size:12px" onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','id',this.value)"></td>
            <td><input type="text" value="<?=esc_attr($f['label'])?>" style="width:100%;font-size:12px" onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','label',this.value)"></td>
            <td><select style="font-size:12px;width:100%" onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','type',this.value)">
              <?php foreach(['text','email','tel','url','date','textarea','select','radio','checkbox'] as $t):?><option value="<?=$t?>"<?=$f['type']===$t?' selected':''?>><?=$t?></option><?php endforeach;?>
            </select></td>
            <td><input type="text" value="<?=esc_attr($f['placeholder']??'')?>" style="width:100%;font-size:12px" onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','placeholder',this.value)"></td>
            <td style="text-align:center"><input type="checkbox"<?=!empty($f['optional'])?' checked':''?> onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','optional',this.checked)"></td>
            <td><button class="button button-small" style="color:#b00" onclick="atpDelField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>')">✕</button></td>
          </tr>
          <?php if(in_array($f['type'],['select','radio','checkbox'])&&isset($f['options'])):?>
          <tr style="background:#fafafa"><td colspan="6" style="padding:6px 8px">
            <label style="font-size:11px;color:#666;font-weight:700">Options (one per line):</label><br>
            <textarea style="width:100%;height:60px;font-size:12px;margin-top:3px" onchange="atpUpdField('<?=esc_attr($q['id'])?>','<?=esc_attr($f['id'])?>','options',this.value.split('\n').filter(x=>x.trim()))"><?=esc_textarea(implode("\n",$f['options']))?></textarea>
          </td></tr>
          <?php endif;?>
          <?php endforeach;?>
          </tbody>
        </table>
        <button class="button button-small" onclick="atpAddField('<?=esc_attr($q['id'])?>')">+ Add field</button>
      </div>
    </div>
    <?php endforeach;?>
    </div>

    <?php elseif($tab==='branding'):?>
    <form method="post" action="<?=esc_url(admin_url('admin-post.php'))?>">
      <?php wp_nonce_field('atp_save_branding','atp_branding_nonce');?>
      <input type="hidden" name="action" value="atp_save_branding">
      <table class="form-table">
        <tr><th><label for="logo_text">Logo Text</label></th><td>
          <input type="text" id="logo_text" name="logo_text" value="<?=esc_attr(strip_tags($s['logo_text']??'America Tracking Polls'))?>" class="regular-text">
          <p class="description">Wrap a word in <code>&lt;span&gt;word&lt;/span&gt;</code> to highlight it in red. E.g. <code>America &lt;span&gt;Tracking&lt;/span&gt; Polls</code></p>
        </td></tr>
        <tr><th><label for="logo_image">Logo Image URL</label></th><td>
          <input type="url" id="logo_image" name="logo_image" value="<?=esc_attr($s['logo_image']??'')?>" class="regular-text">
          <?php if(!empty($s['logo_image'])):?><br><img src="<?=esc_url($s['logo_image'])?>" style="max-height:40px;margin-top:6px;border:1px solid #ddd;padding:4px;border-radius:3px"><?php endif;?>
          <p class="description">If set, replaces the red star icon. Paste any image URL, or use the button below to choose from your Media Library.</p>
          <button type="button" class="button button-small" onclick="atpMedia()" style="margin-top:4px">Choose from Media Library</button>
        </td></tr>
      </table>
      <div style="margin:20px 0 0"><strong>Live preview:</strong></div>
      <div style="background:#0e1235;padding:14px 24px;display:inline-flex;align-items:center;gap:10px;border-radius:6px;margin:10px 0 20px">
        <div id="pp-icon" style="width:28px;height:28px;background:#d42b2b;border-radius:4px;display:flex;align-items:center;justify-content:center"><svg width="14" height="14" viewBox="0 0 24 24" fill="white"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg></div>
        <div id="pp-text" style="font-family:Arial,sans-serif;font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#fff"><?=wp_kses($s['logo_text'],['span'=>[]])?></div>
      </div>
      <?php submit_button('Save Branding');?>
    </form>
    <script>
    document.getElementById('logo_text').addEventListener('input',function(){document.getElementById('pp-text').innerHTML=this.value.replace(/<span>(.*?)<\/span>/g,'<span style="color:#d42b2b">$1</span>');});
    document.getElementById('logo_image').addEventListener('input',function(){if(this.value){document.getElementById('pp-icon').innerHTML='<img src="'+this.value+'" style="width:28px;height:28px;object-fit:contain;border-radius:4px">';}else{document.getElementById('pp-icon').innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="white"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>';}});
    function atpMedia(){if(typeof wp==='undefined'||!wp.media){alert('Media Library not available here. Paste a URL directly.');return;}const f=wp.media({title:'Select Logo',button:{text:'Use this image'},multiple:false});f.on('select',function(){const a=f.state().get('selection').first().toJSON();document.getElementById('logo_image').value=a.url;document.getElementById('logo_image').dispatchEvent(new Event('input'));});f.open();}
    </script>

    <?php elseif($tab==='notifications'):?>
    <form method="post" action="<?=esc_url(admin_url('admin-post.php'))?>">
      <?php wp_nonce_field('atp_save_notifications','atp_notif_nonce');?>
      <input type="hidden" name="action" value="atp_save_notifications">
      <h2 style="font-size:15px;margin-top:0">Email Recipients</h2>
      <p style="color:#666;margin-bottom:16px">Everyone on this list receives an email with the full candidate profile and JSON data when a form is submitted.</p>
      <div id="atp-email-list">
        <?php foreach($s['notify_emails']??[] as $e):?>
        <div class="atp-er" style="display:flex;gap:8px;margin-bottom:8px"><input type="email" name="notify_emails[]" value="<?=esc_attr($e)?>" class="regular-text" placeholder="email@example.com"><button type="button" class="button" onclick="this.parentElement.remove()">Remove</button></div>
        <?php endforeach;?>
      </div>
      <button type="button" class="button" onclick="atpAddEmail()" style="margin-bottom:20px">+ Add Recipient</button>
      <br>
      <table class="form-table" style="margin-top:0">
        <tr><th><label for="notify_subject">Email Subject</label></th><td>
          <input type="text" id="notify_subject" name="notify_subject" value="<?=esc_attr($s['notify_subject']??'New ATP Intake: {candidate_name}')?>" class="regular-text">
          <p class="description">Available placeholders: <code>{candidate_name}</code> <code>{office}</code> <code>{state}</code></p>
        </td></tr>
      </table>
      <?php submit_button('Save Notification Settings');?>
    </form>
    <script>function atpAddEmail(){const d=document.createElement('div');d.className='atp-er';d.style.cssText='display:flex;gap:8px;margin-bottom:8px';d.innerHTML='<input type="email" name="notify_emails[]" class="regular-text" placeholder="email@example.com"><button type="button" class="button" onclick="this.parentElement.remove()">Remove</button>';document.getElementById('atp-email-list').appendChild(d);d.querySelector('input').focus();}</script>
    <?php endif;?>
    </div>

    <script>
    const ATP_AJ='<?=esc_js(admin_url('admin-ajax.php'))?>',ATP_NC='<?=esc_js(wp_create_nonce('atp_admin_settings'))?>';
    let Q=<?=json_encode($s['questions'])?>;

    function atpSave(cb){
      const m=document.getElementById('atp-save-msg');if(m)m.textContent='Saving...';
      const fd=new FormData();fd.append('action','atp_save_questions');fd.append('nonce',ATP_NC);fd.append('questions',JSON.stringify(Q));
      fetch(ATP_AJ,{method:'POST',body:fd}).then(r=>r.json()).then(r=>{if(m){m.textContent=r.success?'Saved.':'Error: '+(r.data||'?');if(r.success)setTimeout(()=>m.textContent='',2000);}if(cb)cb();});
    }
    function atpUpdStep(id,k,v){const s=Q.find(q=>q.id===id);if(s){s[k]=v;atpSave();}}
    function atpUpdField(sid,fid,k,v){const s=Q.find(q=>q.id===sid);if(!s)return;const f=s.fields.find(f=>f.id===fid);if(f){f[k]=v;atpSave();}}
    function atpDelField(sid,fid){if(!confirm('Delete this field?'))return;const s=Q.find(q=>q.id===sid);if(s){s.fields=s.fields.filter(f=>f.id!==fid);atpSave(()=>location.reload());}}
    function atpAddField(sid){const s=Q.find(q=>q.id===sid);if(s){s.fields.push({id:'f_'+Date.now(),label:'New field',type:'text',placeholder:'',optional:true});atpSave(()=>location.reload());}}
    function atpDelStep(id){if(!confirm('Delete this step and all its fields?'))return;Q=Q.filter(q=>q.id!==id);atpSave(()=>location.reload());}
    function atpAddStep(){Q.push({id:'s_'+Date.now(),section:(Q.length+1)+' — New Step',question:'New question',subtitle:'',fields:[{id:'f_'+Date.now(),label:'New field',type:'text',placeholder:'',optional:false}]});atpSave(()=>location.reload());}
    </script>
    <?php
}

/* ════════════════════════════════════════
   ADMIN POST HANDLERS
════════════════════════════════════════ */
add_action('admin_post_atp_save_branding','atp_post_branding');
function atp_post_branding(){
    check_admin_referer('atp_save_branding','atp_branding_nonce');
    if(!current_user_can('manage_options'))wp_die('Unauthorized');
    $s=atp_get_settings();
    $s['logo_text']=wp_kses($_POST['logo_text']??'America Tracking Polls',['span'=>[]]);
    $s['logo_image']=esc_url_raw($_POST['logo_image']??'');
    update_option('atp_settings',$s);
    wp_redirect(admin_url('admin.php?page=atp-settings&tab=branding&saved=1'));exit;
}

add_action('admin_post_atp_save_notifications','atp_post_notifications');
function atp_post_notifications(){
    check_admin_referer('atp_save_notifications','atp_notif_nonce');
    if(!current_user_can('manage_options'))wp_die('Unauthorized');
    $s=atp_get_settings();
    $raw=isset($_POST['notify_emails'])?(array)$_POST['notify_emails']:[];
    $s['notify_emails']=array_values(array_filter(array_map('sanitize_email',$raw)));
    $s['notify_subject']=sanitize_text_field($_POST['notify_subject']??'New ATP Intake: {candidate_name}');
    update_option('atp_settings',$s);
    wp_redirect(admin_url('admin.php?page=atp-settings&tab=notifications&saved=1'));exit;
}

add_action('admin_post_atp_retry_drive_mirror','atp_post_retry_drive_mirror');
function atp_post_retry_drive_mirror(){
    $id=(int)($_GET['id']??0);
    if(!$id||!current_user_can('manage_options'))wp_die('Unauthorized');
    check_admin_referer('atp_retry_drive_mirror_'.$id,'nonce');
    $result=atp_retry_drive_mirror_for_submission($id);
    $status=is_wp_error($result)?'drive_retry_error':'drive_retry_success';
    $msg=is_wp_error($result)?$result->get_error_message():'Drive mirror retried: '.count($result['files']).' file(s) uploaded.';
    set_transient('atp_drive_retry_notice_'.get_current_user_id(),['status'=>$status,'msg'=>$msg],60);
    wp_safe_redirect(admin_url('admin.php?page=atp-candidates&view='.$id));exit;
}

function atp_retry_drive_mirror_for_submission($id){
    if(!function_exists('atp_drive_upload'))return new WP_Error('atp_drive_missing','Drive upload functions are not loaded.');
    if(!function_exists('atp_drive_is_configured')||!atp_drive_is_configured())return new WP_Error('atp_drive_not_configured','Google Drive is not connected or no destination folder is selected.');
    $post=get_post($id);
    if(!$post||$post->post_type!=='atp_candidate')return new WP_Error('atp_drive_bad_submission','Submission not found.');

    $candidate=get_post_meta($id,'display_name',true)?:get_post_meta($id,'legal_name',true)?:$post->post_title;
    $office=get_post_meta($id,'office',true)?:'';
    $fields=['headshot'=>get_post_meta($id,'headshot',true),'logo'=>get_post_meta($id,'logo',true)];
    $additional=get_post_meta($id,'additional_photos',true);
    if(is_array($additional)){
        foreach($additional as $i=>$url)$fields['additional_photos_'.$i]=$url;
    } elseif($additional){
        $fields['additional_photos']=$additional;
    }

    $files=[];
    $sub_folder='';
    foreach($fields as $field=>$url){
        if(!$url||!filter_var($url,FILTER_VALIDATE_URL))continue;
        $attachment_id=attachment_url_to_postid($url);
        if(!$attachment_id)continue;
        $path=get_attached_file($attachment_id);
        if(!$path||!is_readable($path))continue;
        $base_field=preg_replace('/_\d+$/','',$field);
        $mime=get_post_mime_type($attachment_id)?:'application/octet-stream';
        $fake=[
            'name'=>basename($path),
            'type'=>$mime,
            'tmp_name'=>$path,
            'size'=>filesize($path),
        ];
        $drive=atp_drive_upload($fake,$base_field,$candidate,$office,$path);
        if(is_wp_error($drive))return $drive;
        $sub_folder=$drive['sub_folder']??$sub_folder;
        $files[]=[
            'field'=>$base_field,
            'name'=>$drive['name']??basename($path),
            'id'=>$drive['id']??'',
            'url'=>$drive['url']??'',
            'sub_folder'=>$drive['sub_folder']??'',
        ];
    }

    if(empty($files))return new WP_Error('atp_drive_no_files','No readable Media Library files found to mirror.');
    if($sub_folder)update_post_meta($id,'_atp_drive_subfolder_url',esc_url_raw($sub_folder));
    update_post_meta($id,'_atp_drive_files',wp_slash(json_encode($files,JSON_UNESCAPED_UNICODE)));

    $v3=get_post_meta($id,'_v3_json',true);
    $v3_data=$v3?json_decode(wp_unslash($v3),true):[];
    if(is_array($v3_data)){
        if(!isset($v3_data['visual_branding'])||!is_array($v3_data['visual_branding']))$v3_data['visual_branding']=[];
        if($sub_folder)$v3_data['visual_branding']['submission_folder']=$sub_folder;
        $v3_data['visual_branding']['drive_files']=$files;
        update_post_meta($id,'_v3_json',wp_slash(json_encode($v3_data,JSON_UNESCAPED_UNICODE)));
    }

    return ['sub_folder'=>$sub_folder,'files'=>$files];
}

/* ════════════════════════════════════════
   AJAX: SAVE QUESTIONS
════════════════════════════════════════ */
add_action('wp_ajax_atp_save_questions','atp_ajax_questions');
function atp_ajax_questions(){
    check_ajax_referer('atp_admin_settings','nonce');
    if(!current_user_can('manage_options'))wp_send_json_error('Unauthorized');
    $qs=json_decode(wp_unslash($_POST['questions']??''),true);
    if(!is_array($qs))wp_send_json_error('Invalid');
    $s=atp_get_settings();$s['questions']=$qs;update_option('atp_settings',$s);
    wp_send_json_success();
}

/* ════════════════════════════════════════
   AJAX: SAVE SUBMISSION
════════════════════════════════════════ */
add_action('wp_ajax_atp_save','atp_ajax_save');
add_action('wp_ajax_nopriv_atp_save','atp_ajax_save');
function atp_ajax_save(){
    check_ajax_referer('atp_form','nonce');
    $raw=json_decode(wp_unslash($_POST['data']??''),true);
    if(!$raw)wp_send_json_error('Invalid');
    $name=sanitize_text_field($raw['display_name']??$raw['legal_name']??'New Candidate');
    $pid=wp_insert_post(['post_title'=>$name,'post_type'=>'atp_candidate','post_status'=>'publish']);
    if(is_wp_error($pid))wp_send_json_error($pid->get_error_message());
    foreach($raw as $k=>$v){
        $k=sanitize_key($k);
        if($k==='drive_uploads_json'){
            update_post_meta($pid,'_atp_drive_uploads_json',sanitize_textarea_field((string)$v));
            continue;
        }
        is_array($v)?update_post_meta($pid,$k,array_map('sanitize_textarea_field',$v)):update_post_meta($pid,$k,sanitize_textarea_field((string)$v));
    }
    $v3=json_decode(wp_unslash($_POST['v3_json']??''),true);
    if($v3){
        update_post_meta($pid,'_v3_json',wp_slash(json_encode($v3,JSON_UNESCAPED_UNICODE)));
        $sub=$v3['visual_branding']['submission_folder']??'';
        $files=$v3['visual_branding']['drive_files']??[];
        if($sub)update_post_meta($pid,'_atp_drive_subfolder_url',esc_url_raw($sub));
        if(is_array($files)&&!empty($files))update_post_meta($pid,'_atp_drive_files',wp_slash(json_encode($files,JSON_UNESCAPED_UNICODE)));
    }
    atp_send_notifications($raw,$pid);
    wp_send_json_success(['post_id'=>$pid,'name'=>$name]);
}

/* ════════════════════════════════════════
   EMAIL NOTIFICATIONS
════════════════════════════════════════ */
function atp_send_notifications($data,$pid){
    $s=atp_get_settings();$emails=$s['notify_emails']??[];if(empty($emails))return;
    $name=$data['display_name']??$data['legal_name']??'New Candidate';
    $office=$data['office']??'—';$state=$data['state']??'—';
    $subj=str_replace(['{candidate_name}','{office}','{state}'],[$name,$office,$state],$s['notify_subject']??'New ATP Intake: {candidate_name}');
    $view=admin_url('admin.php?page=atp-candidates&view='.$pid);
    $slug=atp_intake_suggested_slug($data);
    $drive=atp_intake_drive_folder_link($pid,$data);
    $bundle_nonce=wp_create_nonce('atp_export');
    $bundle=admin_url('admin-ajax.php?action=atp_export_bundle&id='.$pid.'&nonce='.$bundle_nonce);
    $pairs=[['Candidate',$name],['Suggested slug',$slug],['Office',$office],['State',$state],['Party',$data['party']??'—'],['Election',($data['election_type']??'').($data['election_date']?' — '.$data['election_date']:'')],['Filing URL',$data['filing_url']??'—'],['Committee',$data['committee_name']??'—'],['Survey Focus',$data['primary_survey_focus']??'—'],['Platform Status',$data['fundraising_platform_status']??'—'],['Contact Email',$data['contact_email']??$data['filler_email']??'—'],['Launch Timeline',$data['launch_timeline']??'—'],['Disclaimer',$data['paidfor_text']??'—']];
    $rows='';foreach($pairs as[$l,$v]){if(!$v||$v==='—')continue;$rows.='<tr><td style="padding:8px 12px;border:1px solid #eee;background:#fff;color:#666;width:140px;font-size:13px">'.esc_html($l).'</td><td style="padding:8px 12px;border:1px solid #eee;font-size:13px;font-weight:600">'.esc_html($v).'</td></tr>';}
    $body='<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;max-width:640px;margin:0 auto;padding:20px">';
    $body.='<div style="background:#0e1235;padding:16px 24px;border-radius:6px 6px 0 0;display:flex;align-items:center;gap:12px">';
    $body.='<div style="background:#d42b2b;width:28px;height:28px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:16px">★</div>';
    $body.='<span style="color:white;font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase">AMERICA TRACKING POLLS</span></div>';
    $body.='<div style="background:#f9f9f9;border:1px solid #ddd;border-top:none;padding:24px;border-radius:0 0 6px 6px">';
    $body.='<h2 style="color:#0e1235;margin:0 0 16px;font-size:18px">New Candidate Intake Submission</h2>';
    $body.='<table style="width:100%;border-collapse:collapse;margin-bottom:20px">'.$rows.'</table>';
    $body.='<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px">';
    $body.='<a href="'.esc_url($view).'" style="display:inline-block;background:#d42b2b;color:white;padding:12px 20px;border-radius:4px;text-decoration:none;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">View Submission →</a>';
    $body.='<a href="'.esc_url($bundle).'" style="display:inline-block;background:#0e1235;color:white;padding:12px 20px;border-radius:4px;text-decoration:none;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">Download Bundle (zip)</a>';
    if($drive)$body.='<a href="'.esc_url($drive).'" target="_blank" style="display:inline-block;background:#1a73e8;color:white;padding:12px 20px;border-radius:4px;text-decoration:none;font-weight:700;font-size:12px;letter-spacing:1px;text-transform:uppercase">Open Drive Folder</a>';
    $body.='</div>';
    $body.='<p style="font-size:12px;color:#888;margin:0 0 20px">The <strong>Bundle</strong> is a .zip with three files for the engineer: a REFERENCE.md (links + instructions), the V3 JSON, and a ready-to-paste PROMPT.md (prompt template + V3 inlined). Drop the prompt file into Claude/ChatGPT to generate the candidate site.</p>';
    $body.='<h3 style="color:#666;font-size:12px;text-transform:uppercase;letter-spacing:1px;border-top:1px solid #eee;padding-top:16px;margin:0 0 8px">Full JSON Data</h3>';
    $body.='<pre style="background:#0e1235;color:#7eb8f7;padding:16px;border-radius:4px;font-size:11px;white-space:pre-wrap;word-break:break-all">'.json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).'</pre>';
    $body.='</div></body></html>';
    $h=['Content-Type: text/html; charset=UTF-8'];
    foreach($emails as $e){if(is_email(trim($e)))wp_mail(trim($e),$subj,$body,$h);}
}

/* ════════════════════════════════════════
   INTAKE BUNDLE — slug + Drive helpers, ZIP export
════════════════════════════════════════ */

function atp_intake_suggested_slug($data){
    $base=$data['display_name']??$data['legal_name']??'candidate';
    $year=$data['election_date']?substr($data['election_date'],0,4):date('Y');
    return sanitize_title($base.'-'.$year);
}

function atp_intake_drive_folder_link($pid,$data){
    // Reconstruct the per-submission Drive subfolder URL based on the same naming
    // rule used by atp_drive_upload(): YYYY-MM-DD_Candidate-Name_Office-Slug.
    // We don't store the subfolder ID per-submission yet, so this links to the
    // parent folder if we have it. (Future: persist sub-folder ID at upload time.)
    $cfg=get_option('atp_drive_config',[]);
    $parent=$cfg['folder_id']??'';
    if(!$parent)return '';
    return 'https://drive.google.com/drive/folders/'.rawurlencode($parent);
}

function atp_intake_collect($pid){
    $raw=get_post_meta($pid);$data=[];
    foreach($raw as $k=>$v){if($k[0]==='_'&&$k!=='_v3_json')continue;$data[$k]=maybe_unserialize($v[0]);}
    $v3=get_post_meta($pid,'_v3_json',true);
    if($v3)$data['_v3_json']=json_decode($v3,true);
    return $data;
}

function atp_intake_collect_assets($data){
    // Pull every URL-looking value from the intake data so REFERENCE.md
    // can list each uploaded asset directly. Looks at flat post meta
    // (legacy form field names) and at known V3 paths.
    $assets = [];
    $flat_keys = [
        'headshot'          => 'Headshot',
        'logo'              => 'Logo',
        'additional_photos' => 'Additional photos',
        'video_main'        => 'Main video',
        'video_other'       => 'Other video',
        'logo_url'          => 'Logo URL',
    ];
    foreach ( $flat_keys as $k => $label ) {
        if ( empty( $data[ $k ] ) ) continue;
        $vals = is_array( $data[ $k ] ) ? $data[ $k ] : [ $data[ $k ] ];
        foreach ( $vals as $v ) {
            $v = is_array( $v ) ? ( $v['url'] ?? '' ) : $v;
            if ( $v && filter_var( $v, FILTER_VALIDATE_URL ) ) {
                $assets[] = [ 'label' => $label, 'url' => $v ];
            }
        }
    }
    $v3 = $data['_v3_json'] ?? [];
    $v3_paths = [
        [ 'visual_branding', 'headshot_link',          'V3: headshot_link' ],
        [ 'visual_branding', 'logo_link',              'V3: logo_link' ],
        [ 'visual_branding', 'additional_photos',      'V3: additional_photos' ],
        [ 'video',           'main_video_url',         'V3: main_video_url' ],
        [ 'video',           'other_video_url',        'V3: other_video_url' ],
    ];
    foreach ( $v3_paths as [ $sec, $key, $label ] ) {
        $v = $v3[ $sec ][ $key ] ?? null;
        if ( ! $v ) continue;
        $list = is_array( $v ) ? $v : [ $v ];
        foreach ( $list as $url ) {
            if ( is_array( $url ) ) $url = $url['url'] ?? '';
            if ( $url && filter_var( $url, FILTER_VALIDATE_URL ) ) {
                $assets[] = [ 'label' => $label, 'url' => $url ];
            }
        }
    }
    // De-dup
    $seen = []; $out = [];
    foreach ( $assets as $a ) {
        if ( isset( $seen[ $a['url'] ] ) ) continue;
        $seen[ $a['url'] ] = 1;
        $out[] = $a;
    }
    return $out;
}

function atp_intake_build_reference($pid,$data){
    $name=$data['display_name']??$data['legal_name']??'Candidate';
    $slug=atp_intake_suggested_slug($data);
    $office=$data['office']??'';
    $state=$data['state']??'';
    $view=admin_url('admin.php?page=atp-candidates&view='.$pid);
    $drive=atp_intake_drive_folder_link($pid,$data);
    $media=admin_url('upload.php?mode=grid&search='.rawurlencode($name));
    $assets=atp_intake_collect_assets($data);
    $sub_folder = $data['_atp_drive_subfolder_url'] ?? ($data['_v3_json']['visual_branding']['submission_folder'] ?? '');

    $r ="# ATP Intake — $name\n\n";
    $r.="**Suggested slug:** `$slug`\n";
    $r.="**Office:** $office\n";
    $r.="**State:** $state\n";
    $r.="**WP submission ID:** $pid\n\n";

    $r.="## Quick links\n\n";
    $r.="- [View submission in WP admin]($view)\n";
    $r.="- [WP media library — filter for '".$name."']($media)\n";
    if($sub_folder){
        $r.="- [Open Drive submission subfolder (this candidate)](".$sub_folder.")\n";
    } elseif($drive){
        $r.="- [Open Drive parent folder — drill into the dated subfolder for this candidate]($drive)\n";
    }
    $r.="\n";

    $r.="## Uploaded assets\n\n";
    if(empty($assets)){
        $r.="_No uploaded assets found in this submission._ If files were attempted but aren't here, "
         . "the upload may have failed silently — most commonly because the plugin's `file-upload.php` "
         . "handler wasn't loaded (e.g., on a stale legacy install). Verify the deployed plugin is "
         . "`packages/atp-plugin-core/` (canonical), not the deleted `atp-demo-plugin/` mirror.\n\n";
    } else {
        foreach($assets as $a){
            $r.="- **".$a['label']."** — [".$a['url']."](".$a['url'].")\n";
        }
        $r.="\n_Right-click → Save link as_, or use `wget`/`curl`, to pull each asset locally.\n\n";
    }

    $r.="## Bundle contents\n\n";
    $r.="- `REFERENCE.md` — this file (links + instructions)\n";
    $r.="- `$slug-v3.json` — V3 JSON (intake answers)\n";
    $r.="- `$slug-PROMPT.md` — prompt template + V3 JSON inlined; paste this whole file into Claude or ChatGPT to generate the site content\n\n";

    $r.="## How to generate the candidate site (engineer steps)\n\n";
    $r.="1. **Pull assets.** Download every URL from the *Uploaded assets* section above into a local folder (or just keep them in Drive — you'll re-upload later).\n";
    $r.="2. **Scaffold.** From the monorepo:\n";
    $r.="   ```sh\n   ./scripts/new-site.sh $slug \"$name\" \"$office\"\n   ```\n";
    $r.="3. **Drop in the V3 JSON.** Save the bundle's `$slug-v3.json` as `sites/$slug/intake-v3.json`.\n";
    $r.="4. **Run the AI prompt.** Open Claude or ChatGPT, paste the contents of `$slug-PROMPT.md`. The model returns a Page JSON object.\n";
    $r.="5. **Save the Page JSON.** Save the model's output as `sites/$slug/page-json.json`.\n";
    $r.="6. **Build.**\n";
    $r.="   ```sh\n   ./scripts/build-site.sh $slug\n   ```\n";
    $r.="   This produces `dist/$slug/atp-campaign-site/` — a deployable WP plugin zip.\n";
    $r.="7. **Provision WP.** Spin up a fresh WordPress install for the candidate (SiteGround, etc.).\n";
    $r.="8. **Install + activate** the built plugin (`atp-campaign-site`). Vibe AI will be auto-prompted as a dependency.\n";
    $r.="9. **Import pages.** WP Admin → ATP Demo → Import Pages — click Import on Candidate Landing Page, Issues, Donate, Contact, About, Privacy, Cookie/TCPA, Sign Up, and Brand Guide.\n";
    $r.="10. **Upload media.** WP Admin → Media → Add New — upload the assets you pulled in step 1. Then in *ATP Demo → Candidate Page* re-point any image references to the new media library URLs.\n";
    $r.="11. **Configure white label.** WP Admin → ATP → White Label Settings — set client name, logo, colors. Drive integration is optional on a candidate site.\n";
    $r.="12. **Configure domain + SSL** with the host. Done.\n\n";

    $r.="## Notes\n\n";
    $r.="- The Page JSON output from step 4 may need a quick human review before step 5. The AI prompt is good but campaign content quality matters — check copy for accuracy, tone, factual claims.\n";
    $r.="- If the bundle is missing assets, the form upload likely failed. See the *Uploaded assets* section above for diagnostic guidance.\n";

    return $r;
}

function atp_intake_build_prompt($pid,$data){
    $tpl_path=ATP_DEMO_DIR.'PROMPT-TEMPLATE.md';
    $tpl=is_readable($tpl_path)?file_get_contents($tpl_path):"# (PROMPT-TEMPLATE.md not found in plugin)\n";
    $v3=$data['_v3_json']??$data;
    $json=json_encode($v3,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    if(strpos($tpl,'{INTAKE_JSON}')!==false){
        $tpl=str_replace('{INTAKE_JSON}',$json,$tpl);
    } else {
        $tpl.="\n\n## V3 JSON for this candidate\n\n```json\n".$json."\n```\n";
    }
    $out ="# Generation prompt — ".($data['display_name']??$data['legal_name']??'Candidate')."\n\n";
    $out.="Paste everything below this line into Claude or ChatGPT.\n\n";
    $out.="---\n\n";
    $out.=$tpl;
    return $out;
}

add_action('wp_ajax_atp_export_bundle','atp_export_bundle');
function atp_export_bundle(){
    if(!current_user_can('manage_options'))wp_die('Unauthorized');
    check_ajax_referer('atp_export','nonce');
    $id=(int)$_GET['id'];if(!$id)wp_die('Missing id');
    $data=atp_intake_collect($id);
    $slug=atp_intake_suggested_slug($data);
    $files=[
        'REFERENCE.md'             => atp_intake_build_reference($id,$data),
        $slug.'-v3.json'           => json_encode($data['_v3_json']??$data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),
        $slug.'-PROMPT.md'         => atp_intake_build_prompt($id,$data),
    ];
    if(class_exists('ZipArchive')){
        $tmp=wp_tempnam('atp-bundle-'.$slug);
        $zip=new ZipArchive();
        if($zip->open($tmp,ZipArchive::OVERWRITE)===true){
            foreach($files as $n=>$c)$zip->addFromString($n,$c);
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="'.$slug.'-intake-bundle.zip"');
            header('Content-Length: '.filesize($tmp));
            readfile($tmp);@unlink($tmp);exit;
        }
    }
    // Fallback: tar-like text dump if ZipArchive unavailable on host.
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="'.$slug.'-intake-bundle.txt"');
    foreach($files as $n=>$c){echo "===== $n =====\n\n$c\n\n";}
    exit;
}

/* ════════════════════════════════════════
   AJAX: EXPORTS
════════════════════════════════════════ */
add_action('wp_ajax_atp_export_single','atp_export_single');
function atp_export_single(){
    if(!current_user_can('manage_options'))wp_die('Unauthorized');
    check_ajax_referer('atp_export','nonce');
    $id=(int)$_GET['id'];$raw=get_post_meta($id);$data=[];
    foreach($raw as $k=>$v){$data[$k]=maybe_unserialize($v[0]);}
    $slug=sanitize_title($data['display_name']??$data['legal_name']??'candidate');
    header('Content-Type: application/json');header('Content-Disposition: attachment; filename="'.$slug.'-atp.json"');
    echo json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);exit;
}

add_action('wp_ajax_atp_export_all','atp_export_all');
function atp_export_all(){
    if(!current_user_can('manage_options'))wp_die('Unauthorized');
    check_ajax_referer('atp_export','nonce');
    $posts=get_posts(['post_type'=>'atp_candidate','posts_per_page'=>-1]);$all=[];
    foreach($posts as $p){$raw=get_post_meta($p->ID);$d=[];foreach($raw as $k=>$v){$d[$k]=maybe_unserialize($v[0]);}$all[]=$d;}
    header('Content-Type: application/json');header('Content-Disposition: attachment; filename="atp-all-candidates.json"');
    echo json_encode($all,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);exit;
}

/* ════════════════════════════════════════
   SHORTCODE [atp_intake]
════════════════════════════════════════ */
add_shortcode('atp_intake','atp_shortcode');
function atp_shortcode(){
    $nonce=wp_create_nonce('atp_form');$ajax=admin_url('admin-ajax.php');
    $s=atp_get_settings();$questions=$s['questions'];$total=count($questions);
    $logo_text=$s['logo_text']??'America <span>Tracking</span> Polls';
    $logo_image=$s['logo_image']??'';
    ob_start();?>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
<style>
#atpo{width:100vw;position:relative;left:50%;right:50%;margin-left:-50vw;margin-right:-50vw}
#atpr,#atpr*,#atpr*::before,#atpr*::after{box-sizing:border-box;margin:0;padding:0}
#atpr{--ny:#0e1235;--nm:#151a42;--rd:#d42b2b;--rb:#e83535;--go:#c4a84f;--wh:#fff;
  --w6:rgba(255,255,255,.6);--w2:rgba(255,255,255,.14);--w1:rgba(255,255,255,.07);--gr:rgba(255,255,255,.04);
  position:relative;width:100%;min-height:100vh;background:var(--ny);font-family:'Barlow',sans-serif;color:var(--wh);overflow-x:clip}
#atpc{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0}
.ag{position:absolute;inset:0;z-index:1;pointer-events:none;background-image:linear-gradient(var(--gr) 1px,transparent 1px),linear-gradient(90deg,var(--gr) 1px,transparent 1px);background-size:72px 72px}
.acb{position:absolute;bottom:0;left:0;right:0;z-index:2;height:26%;pointer-events:none;backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);mask-image:linear-gradient(to bottom,transparent 0%,black 30%);-webkit-mask-image:linear-gradient(to bottom,transparent 0%,black 30%)}
.arb{position:absolute;bottom:0;left:0;right:0;z-index:5;height:3px;display:flex;pointer-events:none}
.arr{background:var(--rd);transition:width 1.4s cubic-bezier(.4,0,.2,1)}
.abl{background:#3a5fd9;flex:1}
.ahd{position:sticky;top:0;z-index:200;display:flex;align-items:center;justify-content:space-between;padding:14px 56px;background:rgba(14,18,53,.96);backdrop-filter:blur(14px);border-bottom:1px solid var(--w2)}
body.admin-bar .ahd{top:32px}
@media(max-width:782px){body.admin-bar .ahd{top:46px}}
.alo{display:flex;align-items:center;gap:10px}
.ast{width:28px;height:28px;background:var(--rd);border-radius:4px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ast svg{width:14px;height:14px;fill:white}
.ast img{width:28px;height:28px;object-fit:contain;border-radius:4px}
.alt{font-family:'Barlow Condensed',sans-serif;font-size:13px;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--wh)}
.alt span{color:var(--rd)}
.ahb{font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:.1em;color:var(--w6);text-transform:uppercase}
.apg{position:sticky;top:57px;z-index:199}
body.admin-bar .apg{top:89px}
@media(max-width:782px){body.admin-bar .apg{top:103px}}
.apb{height:2px;background:var(--w1)}
.apf{height:100%;background:var(--rd);transition:width .5s cubic-bezier(.4,0,.2,1);width:0%}
.api{background:rgba(14,18,53,.92);backdrop-filter:blur(8px);padding:6px 56px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.06)}
.aps{font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:.08em;color:var(--w6);text-transform:uppercase}
.apc{font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:.06em;color:var(--rd);text-transform:uppercase}
.ash{position:relative;z-index:10;min-height:calc(100vh - 90px);display:flex;flex-direction:column;align-items:center;padding:64px 56px 140px}
.awp{width:100%;max-width:700px}
.asp{display:none;animation:afd .35s ease forwards}
.asp.active{display:block}
@keyframes afd{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.asn{font-family:'Barlow Condensed',sans-serif;font-size:12px;letter-spacing:.14em;color:var(--rd);text-transform:uppercase;margin-bottom:10px}
.asq{font-family:'Barlow Condensed',sans-serif;font-size:40px;font-weight:600;line-height:1.12;color:var(--wh);margin-bottom:8px}
.asu{font-size:16px;color:var(--w6);margin-bottom:26px;line-height:1.6;max-width:580px}
.afg{display:flex;flex-direction:column;gap:13px;margin-bottom:4px}
.afr{display:flex;gap:10px;flex-wrap:wrap}
.afr>*{flex:1;min-width:180px}
.afc{display:flex;flex-direction:column;gap:5px}
#atpr label.afl{font-size:13px;text-transform:uppercase;letter-spacing:.08em;color:var(--w6);display:block;margin-bottom:3px}
#atpr input[type=text],#atpr input[type=email],#atpr input[type=date],#atpr input[type=url],#atpr input[type=tel],#atpr textarea,#atpr select{
  background:var(--w1)!important;border:1px solid var(--w2)!important;border-radius:6px!important;
  color:var(--wh)!important;font-family:'Barlow',sans-serif!important;font-size:17px!important;
  padding:14px 17px!important;width:100%!important;outline:none!important;
  transition:border-color .2s,background .2s!important;-webkit-appearance:none!important;appearance:none!important;box-shadow:none!important}
#atpr input:focus,#atpr textarea:focus,#atpr select:focus{border-color:var(--rd)!important;background:rgba(212,43,43,.05)!important}
#atpr input::placeholder,#atpr textarea::placeholder{color:var(--w6)!important}
#atpr select{cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(255,255,255,0.4)' stroke-width='1.5' fill='none'/%3E%3C/svg%3E")!important;background-repeat:no-repeat!important;background-position:right 16px center!important}
#atpr select option{background:#151a42;color:var(--wh)}
#atpr textarea{resize:vertical;min-height:100px;line-height:1.6}
.ao{font-family:'Barlow Condensed',sans-serif;font-size:10px;letter-spacing:.05em;color:var(--w6);border:1px solid var(--w2);border-radius:3px;padding:1px 6px;margin-left:6px;vertical-align:middle}
.atp-upload-zone{border:2px dashed var(--w2);border-radius:8px;padding:28px 20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s}
.atp-upload-zone:hover,.atp-upload-zone.drag{border-color:var(--rd);background:rgba(212,43,43,.06)}
.atp-upload-previews{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
.atp-upload-thumb{position:relative;width:80px;height:80px;border-radius:6px;overflow:hidden;border:1px solid var(--w2)}
.atp-upload-thumb img{width:100%;height:100%;object-fit:cover}
.atp-upload-thumb .atp-upload-remove{position:absolute;top:2px;right:2px;width:20px;height:20px;background:rgba(0,0,0,.7);border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;color:#fff;line-height:1}
.atp-upload-progress{height:3px;background:var(--w1);border-radius:2px;margin-top:6px;overflow:hidden}
.atp-upload-progress-bar{height:100%;background:var(--rd);width:0%;transition:width .3s}
.atp-upload-file{display:flex;align-items:center;gap:8px;padding:8px 12px;background:var(--w1);border:1px solid var(--w2);border-radius:6px;font-size:12px;color:rgba(255,255,255,.7);margin-top:6px}
.atp-upload-file .name{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ach{display:flex;flex-direction:column;gap:9px}
.ac{display:flex;align-items:flex-start;gap:13px;padding:14px 17px;border:1px solid var(--w2);border-radius:6px;cursor:pointer;background:var(--w1);transition:border-color .2s,background .2s;user-select:none;opacity:.65}
.ac:hover{border-color:rgba(212,43,43,.5);background:rgba(212,43,43,.06);opacity:1}
.ac.sel{opacity:1}
.ac.sel{border-color:var(--rd);background:rgba(212,43,43,.13)}
.ack{font-family:'Barlow Condensed',sans-serif;font-size:12px;letter-spacing:.06em;color:var(--w6);border:1px solid var(--w2);border-radius:3px;padding:3px 8px;min-width:26px;text-align:center;flex-shrink:0;transition:all .15s}
.ac.sel .ack{border-color:var(--rd);color:var(--rd)}
.acl{font-size:16px;line-height:1.3}
.acn{font-size:13px;color:var(--w6);margin-top:3px;line-height:1.4}
.akg{display:flex;flex-direction:column;gap:9px}
.aki{display:flex;align-items:center;gap:13px;padding:13px 17px;border:1px solid var(--w2);border-radius:6px;cursor:pointer;background:var(--w1);transition:border-color .2s,background .2s;user-select:none}
.aki:hover{border-color:rgba(212,43,43,.5);background:rgba(212,43,43,.06)}
.aki.chk{border-color:var(--rd);background:rgba(212,43,43,.11)}
.akb{width:18px;height:18px;border:1.5px solid var(--w2);border-radius:3px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .15s}
.aki.chk .akb{background:var(--rd);border-color:var(--rd)}
.akb svg{display:none;width:11px;height:9px}
.aki.chk .akb svg{display:block}
.aki .acl{font-size:15px}
#atpr .acta{display:flex;align-items:center;gap:16px;margin-top:30px}
#atpr .abt{font-family:'Barlow Condensed',sans-serif!important;font-size:15px!important;font-weight:600!important;letter-spacing:.12em!important;text-transform:uppercase!important;color:white!important;background:var(--rd)!important;border:none!important;border-radius:6px!important;padding:15px 34px!important;cursor:pointer!important;line-height:1!important;transition:background .15s,transform .1s!important;box-shadow:none!important;display:inline-block!important;text-decoration:none!important}
#atpr .abt:hover{background:var(--rb)!important;color:white!important}
#atpr .abt:active{transform:scale(.98)!important}
#atpr .abk{font-family:'Barlow Condensed',sans-serif!important;font-size:13px!important;letter-spacing:.08em!important;text-transform:uppercase!important;color:var(--w6)!important;background:none!important;border:none!important;cursor:pointer!important;padding:0!important;box-shadow:none!important;text-decoration:none!important;transition:color .15s!important}
#atpr .abk:hover{color:var(--wh)!important}
#atpr .aht{font-size:12px;color:var(--w6)}
#atpr .aht kbd{border:1px solid var(--w2);border-radius:3px;padding:1px 6px;font-size:11px;font-family:inherit}
.apl{display:inline-flex;align-items:center;gap:6px;border:1px solid var(--w2);border-radius:99px;padding:6px 16px;font-family:'Barlow Condensed',sans-serif;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--go);margin-bottom:20px}
.ass{margin-bottom:22px}
.ast2{font-family:'Barlow Condensed',sans-serif;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--rd);margin-bottom:10px;padding-bottom:6px;border-bottom:1px solid var(--w1)}
.asg{display:grid;grid-template-columns:1fr 1fr;gap:9px}
@media(max-width:540px){.asg{grid-template-columns:1fr}}
.asi{background:var(--w1);border:1px solid var(--w2);border-radius:6px;padding:11px 15px}
.asl{font-size:11px;color:var(--w6);text-transform:uppercase;letter-spacing:.07em;margin-bottom:3px}
.asv{font-size:14px;color:var(--wh);font-weight:500;word-break:break-word;line-height:1.4}
.ajson{background:rgba(0,0,0,.45);border:1px solid var(--w2);border-radius:8px;padding:18px;font-family:'Courier New',monospace;font-size:11px;color:#7eb8f7;max-height:260px;overflow-y:auto;white-space:pre;line-height:1.7}
#atpr .abr{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap}
#atpr .abd,#atpr .abc,#atpr .abr2{font-family:'Barlow Condensed',sans-serif!important;font-size:13px!important;letter-spacing:.08em!important;text-transform:uppercase!important;background:var(--w1)!important;border:1px solid var(--w2)!important;border-radius:4px!important;padding:11px 20px!important;cursor:pointer!important;transition:all .15s!important;color:var(--w6)!important;box-shadow:none!important;line-height:1!important}
#atpr .abd:hover,#atpr .abr2:hover,#atpr .abc:hover{color:var(--wh)!important;border-color:rgba(255,255,255,.4)!important;background:var(--w1)!important}
#atpr .abd{background:var(--rd)!important;border-color:var(--rd)!important;color:white!important}
#atpr .abd:hover{background:var(--rb)!important;color:white!important}
.adv{height:1px;background:var(--w1);margin:22px 0}
.asts{font-family:'Barlow Condensed',sans-serif;font-size:11px;letter-spacing:.08em;color:var(--go);text-transform:uppercase;margin-top:10px;min-height:16px}
@media(max-width:640px){.ahd,.api{padding-left:20px;padding-right:20px}.ash{padding:48px 20px 120px}.asq{font-size:28px}.afr>*{min-width:140px}}
</style>

<div id="atpo"><div id="atpr">
  <canvas id="atpc"></canvas>
  <div class="ag"></div><div class="acb"></div>
  <div class="arb"><div class="arr" id="atpRR" style="width:50%"></div><div class="abl"></div></div>

  <header class="ahd">
    <div class="alo">
      <?php if($logo_image):?><div class="ast"><img src="<?=esc_url($logo_image)?>" alt="Logo"></div><?php else:?>
      <div class="ast"><svg viewBox="0 0 24 24"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg></div><?php endif;?>
      <div class="alt"><?=wp_kses($logo_text,['span'=>[]])?></div>
    </div>
    <div class="ahb">Candidate Intake</div>
  </header>

  <div class="apg">
    <div class="apb"><div class="apf" id="atpF"></div></div>
    <div class="api"><div class="aps" id="atpSt">Step 1 of <?=esc_html($total)?></div><div class="apc" id="atpSc"><?=esc_html($questions[0]['section']??'')?></div></div>
  </div>

  <main class="ash"><div class="awp" id="atpW">
  <?php foreach($questions as $qi=>$q):
      $sn=$qi+1;$first=$qi===0;$last=$qi===($total-1);
      $keys=['A','B','C','D','E','F','G','H','I','J'];
  ?>
  <div class="asp<?=$first?' active':''?>" id="as<?=$sn?>">
    <div class="asn"><?=esc_html($q['section'])?></div>
    <div class="asq"><?=esc_html($q['question'])?></div>
    <?php if(!empty($q['subtitle'])):?><div class="asu"><?=esc_html($q['subtitle'])?></div><?php endif;?>
    <?php if($last):?><div id="atpSumContent" style="text-align:left;margin:20px 0"></div><?php endif;?>
    <div class="afg">
    <?php
    $fields=$q['fields'];$fi=0;
    while($fi<count($fields)):
        $f=$fields[$fi];$tp=$f['type'];$fid=esc_attr($f['id']);$lbl=esc_html($f['label']);$ph=esc_attr($f['placeholder']??'');$opt=!empty($f['optional']);$opts=$f['options']??[];$cond=$f['cond']??'';
        if($cond):?><div class="<?=esc_attr($cond)?>" id="afc_<?=$fid?>" style="display:none"><?php endif;
        if($tp==='radio'):?>
      <?php if($lbl):?><label class="afl" style="margin-bottom:8px;display:block"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
      <div class="ach" id="ac_<?=$fid?>">
        <?php foreach($opts as $oi=>$o):$pts=explode(':',$o,2);?>
        <div class="ac" onclick="AP('<?=$fid?>','<?=esc_js(trim($pts[0]))?>',this)">
          <div class="ack"><?=$keys[$oi]??($oi+1)?></div>
          <div><div class="acl"><?=esc_html(trim($pts[0]))?></div><?php if(!empty($pts[1])):?><div class="acn"><?=esc_html(trim($pts[1]))?></div><?php endif;?></div>
        </div>
        <?php endforeach;?>
      </div>
    <?php elseif($tp==='checkbox'):$descs=$f['descs']??[];$subh=$f['subheaders']??[];$cenums=$f['enum']??[];?>
      <?php if($lbl):?><label class="afl" style="margin-bottom:4px;display:block"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
      <?php if($ph):?><div style="font-size:12px;color:rgba(255,255,255,.45);margin-bottom:10px"><?=esc_html($ph)?></div><?php endif;?>
      <div class="akg" id="ak_<?=$fid?>"<?php if(!empty($f['max'])):?> data-max="<?=(int)$f['max']?>"<?php endif;?>>
        <?php foreach($opts as $oi=>$o):?>
        <?php if(isset($subh[$oi])):?><div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.35);padding:<?=$oi?'14px':'0'?> 0 6px"><?=esc_html($subh[$oi])?></div><?php endif;?>
        <div class="aki" onclick="AK(this)"<?php if(!empty($cenums[$oi])):?> data-value="<?=esc_attr($cenums[$oi])?>"<?php endif;?>><div class="akb"><svg viewBox="0 0 10 8" fill="none"><polyline points="1,4 4,7 9,1" stroke="white" stroke-width="1.5"/></svg></div><div><div class="acl"><?=esc_html($o)?></div><?php if(!empty($descs[$oi])):?><div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px;line-height:1.4"><?=esc_html($descs[$oi])?></div><?php endif;?></div></div>
        <?php endforeach;?>
      </div>
    <?php elseif($tp==='select'):$helper=$f['helper']??'';?>
      <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
        <select id="<?=$fid?>"><?php $enums=$f['enum']??[];?><option value="">Select</option><?php foreach($opts as $oi=>$o):?><option value="<?=esc_attr($enums[$oi]??$o)?>"><?=esc_html($o)?></option><?php endforeach;?></select>
        <?php if($helper):?><div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:8px;line-height:1.6"><?=$helper?></div><?php endif;?>
      </div>
    <?php elseif($tp==='textarea'):?>
      <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
        <textarea id="<?=$fid?>" placeholder="<?=$ph?>"></textarea>
      </div>
    <?php elseif($tp==='file'):$maxFiles=(int)($f['max_files']??1);$accept=esc_attr($f['accept']??'');$maxMB=(int)($f['max_size']??10);?>
      <div class="afc">
        <?php if($lbl):?><label class="afl"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
        <div class="atp-upload" id="upload_<?=$fid?>" data-field="<?=$fid?>" data-accept="<?=$accept?>" data-max-size="<?=$maxMB?>" data-max-files="<?=$maxFiles?>">
          <div class="atp-upload-zone" data-atp-zone="<?=$fid?>">
            <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <div style="font-size:13px;color:rgba(255,255,255,.5);margin-top:8px">Drop file<?=$maxFiles>1?'s':''?> here or <span style="color:var(--rd);cursor:pointer">browse</span></div>
            <div style="font-size:11px;color:rgba(255,255,255,.3);margin-top:4px"><?=$ph?></div>
          </div>
          <input type="file" id="file_<?=$fid?>" accept="<?=$accept?>" <?=$maxFiles>1?'multiple':''?> style="display:none" data-atp-file="<?=$fid?>">
          <div class="atp-upload-status" id="status_<?=$fid?>" style="display:none;font-size:12px;color:rgba(255,255,255,.6);margin-top:6px"></div>
          <div class="atp-upload-previews" id="previews_<?=$fid?>"></div>
          <div class="atp-upload-error" id="error_<?=$fid?>" style="display:none;color:#E60000;font-size:12px;margin-top:6px"></div>
        </div>
      </div>
    <?php else:
        $nxt=$fields[$fi+1]??null;
        $pair=$nxt&&!in_array($nxt['type'],['textarea','radio','checkbox','select','date'])&&!in_array($tp,['date']);
        if($pair):$f2=$nxt;$fid2=esc_attr($f2['id']);$lbl2=esc_html($f2['label']);$ph2=esc_attr($f2['placeholder']??'');$opt2=!empty($f2['optional']);?>
      <div class="afr">
        <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?><input type="<?=$tp?>" id="<?=$fid?>" placeholder="<?=$ph?>"></div>
        <div class="afc"><?php if($lbl2):?><label class="afl" for="<?=$fid2?>"><?=$lbl2?><?php if($opt2):?><span class="ao">optional</span><?php endif;?></label><?php endif;?><input type="<?=esc_attr($f2['type'])?>" id="<?=$fid2?>" placeholder="<?=$ph2?>"></div>
      </div>
      <?php $fi++;else:?>
      <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?><input type="<?=$tp?>" id="<?=$fid?>" placeholder="<?=$ph?>"></div>
      <?php endif;endif;if($cond):?></div><?php endif;$fi++;endwhile;?>
    </div>
    <div class="acta">
      <?php if(!$first):?><button class="abk" onclick="AG(<?=$sn-1?>)">← Back</button><?php endif;?>
      <?php if(!$last):?><button class="abt" onclick="AG(<?=$sn+1?>)">Continue</button>
      <?php else:?><button class="abt" id="atpGenBtn" onclick="AF()" disabled style="opacity:.4;cursor:not-allowed">Generate Profile →</button><?php endif;?>
      <?php if($first):?><div class="aht"><kbd>Enter ↵</kbd></div><?php endif;?>
    </div>
  </div>
  <?php endforeach;?>

  <div class="asp" id="as-sum">
    <div class="apl"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20,6 9,17 4,12"/></svg> Intake Complete</div>
    <div class="asq" id="atpST" style="font-size:32px;margin-bottom:24px">Profile captured.</div>
    <div id="atpSB"></div>
    <div class="adv"></div>
    <div style="font-family:'Barlow Condensed',sans-serif;font-size:12px;letter-spacing:.1em;text-transform:uppercase;color:var(--rd);margin-bottom:10px">Raw JSON</div>
    <div class="ajson" id="atpJ"></div>
    <div class="abr">
      <button class="abd" onclick="AD()">Download JSON</button>
      <button class="abc" id="atpCB" onclick="AC()">Copy JSON</button>
      <button class="abr2" onclick="AR()">Start Over</button>
    </div>
    <div class="asts" id="atpSS"></div>
  </div>
  </div></main>
</div></div>

<script>
(function(){
const TOT=<?=$total?>,LS='atp_d',AJ='<?=esc_js($ajax)?>',NC='<?=esc_js($nonce)?>';
const SECS=<?=json_encode(array_column($questions,'section'))?>;
let cur=1;const D={},CK={};

(function(){try{
  const sv=localStorage.getItem(LS);if(!sv)return;
  const p=JSON.parse(sv);Object.assign(D,p.d||{});Object.assign(CK,p.c||{});
  Object.keys(D).forEach(k=>{const e=document.getElementById(k);if(!e)return;if(Array.isArray(D[k]))e.value=D[k].join('\n');else e.value=D[k];});
  Object.keys(CK).forEach(g=>{const items=CK[g]||[];const el=document.getElementById(g);if(!el)return;el.querySelectorAll('.aki').forEach(i=>{const l=i.querySelector('.acl');if(l&&items.includes(l.textContent.trim()))i.classList.add('chk');});});
  Object.keys(D).forEach(k=>{const g=document.getElementById('ac_'+k);if(!g||!D[k])return;g.querySelectorAll('.ac').forEach(c=>{if(c.querySelector('.acl')?.textContent===D[k])c.classList.add('sel');});});
}catch(e){}})();

function sv(){try{localStorage.setItem(LS,JSON.stringify({d:D,c:CK}));}catch(e){}}
document.querySelectorAll('#atpr input[type=date]').forEach(function(e){e.addEventListener('change',function(){e.dataset.touched='1';});});
function vl(id){const e=document.getElementById(id);return e?e.value.trim():'';}
function ca(){
  document.querySelectorAll('#atpr input[type=text],#atpr input[type=email],#atpr input[type=url],#atpr input[type=tel],#atpr textarea,#atpr select').forEach(e=>{if(e.id&&e.value.trim())D[e.id]=e.value.trim();});
  document.querySelectorAll('#atpr input[type=date]').forEach(e=>{if(e.id&&e.dataset.touched)D[e.id]=e.value;});
  document.querySelectorAll('#atpr .akg').forEach(g=>{const k=g.id.replace('ak_','');D[k]=CK[g.id]||[];});
  sv();
}

function buildSummary(){
  var h='';var ss='color:rgba(255,255,255,.5);font-size:11px;letter-spacing:.1em;text-transform:uppercase;margin:20px 0 8px';
  var ps=['Home','About','Issues','Sign-Up','Donate','Contact','Privacy Policy','Cookie-Tracking-SMS Compliance Policy'];
  if(D.survey_page_wanted&&D.survey_page_wanted.indexOf('Yes')===0)ps.push('Survey');
  h+='<div style="'+ss+'">Your Standard Campaign Website includes:</div>';
  h+='<div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px">';
  ps.forEach(function(p){h+='<span style="background:rgba(255,255,255,.08);padding:4px 10px;border-radius:3px;font-size:12px;color:rgba(255,255,255,.7)">'+p+'</span>';});
  h+='</div>';
  var conds=[];
  if(D.endorsements_about)conds.push('Your endorsements will appear on your About page.');
  if(D.video_main)conds.push('Your campaign video will be embedded on your homepage.');
  if(D.campaign_email_needed&&D.campaign_email_needed.indexOf('Yes')===0)conds.push('A campaign email address will be set up on your domain.');
  if(conds.length){h+='<div style="margin-bottom:16px">';conds.forEach(function(c){h+='<div style="font-size:13px;color:rgba(255,255,255,.6);margin-bottom:4px">✓ '+c+'</div>';});h+='</div>';}
  h+='<div style="'+ss+'">Key Details</div>';
  var kd=[['Candidate',D.display_name||D.legal_name],['Office',D.office],['District',D.district],['State',D.state],['Party',D.party],['Election Year',D.election_year],['Survey Focus',D.primary_survey_focus],['Domain',D.domain_preferred],['Launch',D.launch_timeline],['Launch Date',D.launch_date]];
  h+='<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 16px;margin-bottom:16px">';
  kd.forEach(function(r){if(r[1])h+='<div style="font-size:12px"><span style="color:rgba(255,255,255,.4)">'+r[0]+':</span> <span style="color:rgba(255,255,255,.8)">'+r[1]+'</span></div>';});
  h+='</div>';
  var svcs=(Array.isArray(D.additional_services)?D.additional_services:[]).concat(Array.isArray(D.tier2_pages)?D.tier2_pages:[]).concat(Array.isArray(D.additional_survey_focuses)?D.additional_survey_focuses:[]);
  if(svcs.length){h+='<div style="'+ss+'">Additional Services of Interest</div><div style="margin-bottom:16px">';svcs.forEach(function(s){h+='<div style="font-size:12px;color:rgba(255,255,255,.6);margin-bottom:3px">• '+s+'</div>';});h+='<div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:8px">These will be discussed separately with your ATP team.</div></div>';}
  var el=document.getElementById('atpSumContent');if(el)el.innerHTML=h;
}
window.AG=function(n){ca();cur=n;if(n===TOT)buildSummary();
  const p=n>TOT?100:((n-1)/TOT)*100;
  document.getElementById('atpF').style.width=p+'%';
  document.getElementById('atpSt').textContent=n>TOT?'Complete':'Step '+n+' of '+TOT;
  document.getElementById('atpSc').textContent=SECS[n-1]||'';
  document.querySelectorAll('.asp').forEach(s=>s.classList.remove('active'));
  const id=n==='s'?'as-sum':'as'+n;
  const el=document.getElementById(id);if(el){el.classList.remove('active');void el.offsetWidth;el.classList.add('active');}
  document.getElementById('atpr').scrollIntoView({behavior:'smooth',block:'start'});
};

window.AP=function(k,v,el){
  const g=el.closest('.ach');g.querySelectorAll('.ac').forEach(c=>c.classList.remove('sel'));
  el.classList.add('sel');D[k]=v;sv();CV();
};

function CV(){
  function sh(id,show){var e=document.getElementById(id);if(e)e.style.display=show?'':'none';}
  function shAll(cls,show){document.querySelectorAll('.'+cls).forEach(function(e){e.style.display=show?'':'none';});}
  var yes=function(k){return D[k]&&D[k].indexOf('Yes')===0;};
  var eq=function(k,v){return D[k]===v;};
  // Step 10: survey fields only if Yes
  shAll('cond-survey',yes('survey_page_wanted'));
  // Step 10: custom label only if Custom selected
  sh('afc_survey_page_label_custom',eq('survey_page_label','Custom (enter below)'));
  // Step 12: custom button label
  shAll('cond-donation-custom',eq('donation_button_label','Custom (enter below)'));
  // Step 12: text-to-donate fields
  shAll('cond-text-donate',yes('accept_text_donations'));
  // Step 13: hosting/credentials only if replace/rebuild
  shAll('cond-rebuild',eq('domain_status','I have an existing website to replace or rebuild'));
  // Step 14: referral specify
  shAll('cond-referral-other',eq('referral_source','Other'));
  // Step 15: Section C only if survey page wanted, and filter out selected primary focus
  var surveyYes=yes('survey_page_wanted');
  shAll('cond-survey-focuses',surveyYes);
  if(surveyYes){
    var pf=D.primary_survey_focus||'';
    var sfGroup=document.querySelector('#ak_additional_survey_focuses');
    if(sfGroup){sfGroup.querySelectorAll('.aki').forEach(function(item){
      var label=item.querySelector('.acl');
      if(label)item.style.display=(label.textContent.trim()===pf)?'none':'';
    });}
  }
}
document.addEventListener('change',function(e){if(e.target.tagName==='SELECT'){if(e.target.id)D[e.target.id]=e.target.value;CV();}});
CV();

window.AK=function(el){
  const g=el.closest('.akg');const gid=g.id;if(!CK[gid])CK[gid]=[];
  const maxSel=parseInt(g.dataset.max||'0');
  const t=el.dataset.value||el.querySelector('.acl').textContent.trim();
  if(!el.classList.contains('chk')&&maxSel>0&&CK[gid].length>=maxSel){
    var w=g.querySelector('.akg-max-warn');
    if(!w){w=document.createElement('div');w.className='akg-max-warn';w.style.cssText='color:#E60000;font-size:12px;margin-top:6px';g.appendChild(w);}
    w.textContent='Maximum '+maxSel+' selections allowed.';setTimeout(function(){if(w)w.remove();},3000);return;
  }
  el.classList.toggle('chk');
  if(el.classList.contains('chk')){if(!CK[gid].includes(t))CK[gid].push(t);}
  else{CK[gid]=CK[gid].filter(i=>i!==t);}sv();
  checkGate();
};
function checkGate(){
  var s1=CK['ak_scope_acknowledgment']||[];
  var s2=CK['ak_compliance_acknowledgment']||[];
  var ok=s1.length>0&&s2.length>0;
  var btn=document.getElementById('atpGenBtn');
  if(btn){btn.disabled=!ok;btn.style.opacity=ok?'1':'.4';btn.style.cursor=ok?'pointer':'not-allowed';}
}

var UPLOADS={};
function atpLog(){try{console.log.apply(console,['[atp-upload]'].concat([].slice.call(arguments)));}catch(_){}}
function atpSetStatus(fid,msg,kind){
  var s=document.getElementById('status_'+fid);
  if(!s)return;
  s.textContent=msg||'';
  s.style.color=kind==='err'?'#E60000':(kind==='ok'?'#28a745':'rgba(255,255,255,.6)');
  s.style.display=msg?'block':'none';
}
function atpHandleDrop(e,fid){
  var files=e.dataTransfer.files;
  atpLog('drop',fid,'files=',files&&files.length);
  atpHandleFiles(files,fid);
}
function atpHandleFiles(files,fid){
  atpLog('handleFiles',fid,'count=',files?files.length:0);
  var el=document.getElementById('upload_'+fid);
  if(!el){atpLog('ABORT no upload_ el',fid);return;}
  var rawAccept=(el.dataset.accept||'').trim();
  var accept=rawAccept?rawAccept.split(',').map(function(s){return s.trim().toLowerCase();}).filter(Boolean):[];
  var maxMB=parseInt(el.dataset.maxSize||'10');
  var maxFiles=parseInt(el.dataset.maxFiles||'1');
  var errEl=document.getElementById('error_'+fid);
  if(errEl){errEl.style.display='none';}
  if(!UPLOADS[fid])UPLOADS[fid]=[];
  for(var i=0;i<files.length;i++){
    if(UPLOADS[fid].length>=maxFiles){var m='Maximum '+maxFiles+' file'+(maxFiles>1?'s':'')+' allowed.';if(errEl){errEl.textContent=m;errEl.style.display='block';}atpSetStatus(fid,m,'err');break;}
    var f=files[i];
    var ext='.'+(f.name.split('.').pop()||'').toLowerCase();
    if(accept.length&&accept.indexOf(ext)===-1){var m2=f.name+': file type not accepted. Use '+accept.join(', ');if(errEl){errEl.textContent=m2;errEl.style.display='block';}atpSetStatus(fid,m2,'err');continue;}
    if(f.size>maxMB*1024*1024){var m3=f.name+': exceeds '+maxMB+' MB limit.';if(errEl){errEl.textContent=m3;errEl.style.display='block';}atpSetStatus(fid,m3,'err');continue;}
    UPLOADS[fid].push(f);
    atpSetStatus(fid,'Selected '+f.name+' — uploading…');
    atpLog('accepted',f.name,f.size,'-> upload');
    atpUploadFile(f,fid);
  }
}
window.atpHandleDrop=atpHandleDrop;
window.atpHandleFiles=atpHandleFiles;
function atpUploadFile(file,fid){
  var fd=new FormData();
  fd.append('action','atp_upload_file');
  fd.append('nonce',NC);
  fd.append('file',file);
  fd.append('field',fid);
  fd.append('candidate',D.display_name||D.legal_name||'candidate');
  fd.append('office',D.office||'');
  var prev=document.getElementById('previews_'+fid);
  var thumb=document.createElement('div');thumb.className='atp-upload-thumb';
  if(file.type.startsWith('image/')){
    var img=document.createElement('img');
    var reader=new FileReader();
    reader.onload=function(e){img.src=e.target.result;};
    reader.readAsDataURL(file);
    thumb.appendChild(img);
  } else {
    thumb.innerHTML='<div style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:var(--w1);font-size:10px;color:rgba(255,255,255,.5)">'+file.name.split('.').pop().toUpperCase()+'</div>';
  }
  var removeBtn=document.createElement('div');removeBtn.className='atp-upload-remove';removeBtn.textContent='×';
  removeBtn.onclick=function(){thumb.remove();UPLOADS[fid]=UPLOADS[fid].filter(function(u){return u!==file;});atpUpdateFileData(fid);};
  thumb.appendChild(removeBtn);
  prev.appendChild(thumb);
  var progress=document.createElement('div');progress.className='atp-upload-progress';
  var bar=document.createElement('div');bar.className='atp-upload-progress-bar';
  progress.appendChild(bar);thumb.appendChild(progress);
  var xhr=new XMLHttpRequest();
  xhr.upload.onprogress=function(e){if(e.lengthComputable)bar.style.width=(e.loaded/e.total*100)+'%';};
  xhr.onload=function(){
    progress.remove();
    atpLog('xhr.onload',fid,'status=',xhr.status,'len=',(xhr.responseText||'').length);
    try{
      var r=JSON.parse(xhr.responseText);
      if(r.success){
        file._url=r.data.url;file._id=r.data.id;file._drive=r.data.drive||null;
        atpUpdateFileData(fid);
        atpSetStatus(fid,'Uploaded '+file.name,'ok');
        atpLog('success',fid,r.data&&r.data.url);
      } else {
        thumb.style.borderColor='#E60000';
        var errEl=document.getElementById('error_'+fid);
        var emsg='Upload failed: '+(r.data||'unknown error');
        if(errEl){errEl.textContent=emsg;errEl.style.display='block';}
        atpSetStatus(fid,emsg,'err');
        atpLog('server-error',fid,r.data);
      }
    }catch(e){
      thumb.style.borderColor='#E60000';
      var errEl=document.getElementById('error_'+fid);
      var emsg='Upload failed (HTTP '+xhr.status+'). The server response was not JSON — usually a WAF or PHP error. Check Network tab.';
      if(errEl){errEl.textContent=emsg;errEl.style.display='block';}
      atpSetStatus(fid,emsg,'err');
      atpLog('parse-error',fid,'status=',xhr.status,'body=',(xhr.responseText||'').slice(0,200));
    }
  };
  xhr.onerror=function(){
    progress.remove();
    thumb.style.borderColor='#E60000';
    var errEl=document.getElementById('error_'+fid);
    var emsg='Upload failed: network error reaching '+AJ+'. WAF, firewall, or offline?';
    if(errEl){errEl.textContent=emsg;errEl.style.display='block';}
    atpSetStatus(fid,emsg,'err');
    atpLog('xhr.onerror',fid);
  };
  xhr.open('POST',AJ);xhr.send(fd);
  atpLog('xhr.send',fid,file.name,file.size);
}
function atpUpdateFileData(fid){
  var files=(UPLOADS[fid]||[]).filter(function(f){return f._url;});
  var el=document.getElementById('upload_'+fid);
  var maxFiles=parseInt(el.dataset.maxFiles||'1');
  if(maxFiles===1){D[fid]=files.length?files[0]._url:'';}
  else{D[fid]=files.map(function(f){return f._url;});}
  var driveFiles=[];
  Object.keys(UPLOADS).forEach(function(field){
    (UPLOADS[field]||[]).forEach(function(f){
      if(f._drive){
        driveFiles.push({field:field,name:f._drive.name||f.name,id:f._drive.id||'',url:f._drive.url||'',sub_folder:f._drive.sub_folder||''});
      }
    });
  });
  if(driveFiles.length){
    D.drive_uploads_json=JSON.stringify(driveFiles);
    var folderMatch=driveFiles.find(function(f){return f.sub_folder;});
    D.atp_drive_subfolder_url=folderMatch?folderMatch.sub_folder:'';
  }
  sv();
}

function buildV3(){
  var ps=['Home','About','Issues','Sign-Up','Donate','Contact','Privacy Policy','Cookie-Tracking-SMS Compliance Policy'];
  if(D.survey_page_wanted&&D.survey_page_wanted.indexOf('Yes')===0)ps.push('Survey');
  return {
    meta:{form_version:'3.0',candidate_id:0,submitted_at:new Date().toISOString(),status:'new'},
    source_check:{submitter_name:D.filler_name||'',submitter_email:D.filler_email||'',submitter_phone:D.filler_phone||'',submitter_role:D.filler_role||'',filing_url:D.filing_url||'',ballotpedia_url:D.ballotpedia_url||'',existing_website:D.existing_website||''},
    identity:{organization_type:D.org_type||'',legal_name:D.legal_name||'',display_name:D.display_name||'',ballot_name:D.ballot_name||'',office_sought:D.office||'',district:D.district||'',seat_number:D.seat_number||'',state:D.state||'',party:D.party||'',election_year:D.election_year||'',election_date:D.election_date||'',election_type:D.election_type||'',race_status:D.position||''},
    contacts:{primary_contact_name:D.contact_name||'',primary_contact_role:D.contact_role||'',primary_contact_email:D.contact_email||'',primary_contact_phone:D.contact_phone||'',campaign_mailing_address:D.contact_address||'',campaign_manager_name:D.manager_name||'',campaign_manager_email:D.manager_email||'',campaign_manager_phone:D.manager_phone||'',treasurer_name:D.treasurer_name||'',treasurer_email:D.treasurer_email||'',treasurer_phone:D.treasurer_phone||'',treasurer_mailing_address:D.treasurer_address||''},
    bio_messaging:{ballotpedia_survey_status:D.ballotpedia_status||'',homepage_intro:D.homepage_intro||'',full_bio:D.bio_full||'',why_running:D.why_running||'',tagline:D.tagline||'',differentiator:D.differentiator||'',key_messages:D.key_messages||'',policy_passions:D.policy_passions||'',endorsements_list:D.endorsements_about||''},
    platform_issues:{issue_categories:Array.isArray(D.issue_categories)?D.issue_categories:[],positions:D.issue_positions||'',opponents_missing_issue:D.opponents_missing_issue||'',evolved_position:D.changed_position||''},
    background:{current_profession:D.profession||'',current_role_title:D.current_role||'',previous_experience:D.previous_experience||'',education_1:D.education_1||'',education_2:D.education_2||'',education_3:D.education_3||'',military_branch:D.military_branch||'',military_years:D.military_years||''},
    visual_branding:{headshot_link:D.headshot||'',logo_link:D.logo||'',additional_photos_link:Array.isArray(D.additional_photos)?D.additional_photos:(D.additional_photos?[D.additional_photos]:[]),submission_folder:D.atp_drive_subfolder_url||'',drive_files:D.drive_uploads_json?JSON.parse(D.drive_uploads_json):[],primary_color:D.color_primary||'',secondary_color:D.color_secondary||'',accent_color:D.color_accent||'',visual_style:D.visual_style||'',design_notes:D.design_notes||''},
    social_media:{facebook:D.facebook||'',x_twitter:D.twitter_x||'',instagram:D.instagram||'',youtube:D.youtube||'',tiktok:D.tiktok||'',linkedin:D.linkedin||'',other_platform:D.social_other||''},
    video:{main_video_url:D.video_main||'',other_video_assets:D.video_other||''},
    survey:{include_survey_page:D.survey_page_wanted||'',primary_focus:D.primary_survey_focus||'',page_name:D.survey_page_label||'',custom_page_label:D.survey_page_label_custom||'',placement:D.survey_display||'',intro_text:D.survey_intro_text||'',existing_survey_link:D.existing_survey_link||''},
    legal_compliance:{committee_name:D.committee_name||'',paid_for_by:D.paidfor_text||'',jurisdiction:D.jurisdiction||'',committee_finance_id:D.committee_id||'',committee_mailing_address:D.committee_address||'',campaign_phone_legal:D.campaign_phone||'',campaign_email_legal:D.campaign_email||'',privacy_contact_email:D.privacy_contact_email||'',privacy_contact_phone:D.privacy_contact_phone||'',privacy_contact_address:D.privacy_contact_address||'',uses_cookies_analytics:D.uses_cookies||'',will_send_texts:D.will_send_texts||'',sms_message_types:Array.isArray(D.sms_categories)?D.sms_categories:[],sms_optin_language:D.sms_optin_language||'',survey_collects_contact:D.survey_follow_up||'',donations_solicited_by_text:D.donations_by_text||'',uses_third_party_analytics:D.third_party_analytics||'',shares_data_with_vendors:D.shares_data||'',service_provider_names:D.service_providers||''},
    fundraising:{needs_donation_page:D.donation_needed||'',platform:D.donation_platform||'',platform_status:D.fundraising_platform_status||'',donation_page_url:D.donation_url||'',embed_code:D.donation_embed_code||'',button_label:D.donation_button_label||'',custom_button_label:D.donation_button_custom||'',accepts_text_donations:D.accept_text_donations||'',text_donate_processor:D.text_donation_processor||'',text_donate_accreditation:D.text_donation_accreditation||''},
    domain_setup:{domain_status:D.domain_status||'',preferred_domain:D.domain_preferred||'',primary_domain:D.domain_primary||'',redirect_domains:D.domain_redirects||'',registrar:D.domain_registrar||'',current_hosting_provider:D.hosting_provider||'',has_login_credentials:D.domain_credentials||'',needs_campaign_email:D.campaign_email_needed||''},
    approval_timeline:{approver_same_as_primary:D.approver_same||'',approver_name:D.approver_name||'',approver_email:D.approver_email||'',copy_help_needed:D.copy_help||'',launch_timeline:D.launch_timeline||'',launch_date:D.launch_date||'',preferred_communication:D.comm_pref||'',referral_source:D.referral_source||'',referral_source_other:D.referral_source_other||'',additional_notes:D.open_notes||''},
    additional_services:{services_interest:Array.isArray(D.additional_services)?D.additional_services:[],tier2_pages_interest:Array.isArray(D.tier2_pages)?D.tier2_pages:[],additional_surveys_interest:Array.isArray(D.additional_survey_focuses)?D.additional_survey_focuses:[]},
    acknowledgment:{scope_acknowledged:!!(D.scope_acknowledgment&&D.scope_acknowledgment.length),compliance_acknowledged:!!(D.compliance_acknowledgment&&D.compliance_acknowledgment.length)},
    pages_standard:ps
  };
}

window.AF=function(){
  ca();
  document.getElementById('atpF').style.width='100%';
  document.getElementById('atpSt').textContent='Complete';
  document.getElementById('atpSc').textContent='';
  document.querySelectorAll('.asp').forEach(s=>s.classList.remove('active'));
  const s=document.getElementById('as-sum');s.classList.remove('active');void s.offsetWidth;s.classList.add('active');
  const nm=D.display_name||D.legal_name||'Candidate';
  document.getElementById('atpST').textContent=nm+' — profile captured.';
  RS();SW();
  document.getElementById('atpr').scrollIntoView({behavior:'smooth',block:'start'});
};

function RS(){
  const secs=[
    {t:'Candidate & Race',r:[['Name',D.legal_name],['Display',D.display_name],['Ballot Name',D.ballot_name],['Office',D.office],['District',D.district],['State',(D.state||'')+(D.party?' — '+D.party:'')],['Election',(D.election_type||'')+(D.election_year?' '+D.election_year:'')+(D.election_date?' — '+D.election_date:'')],['Position',D.position]]},
    {t:'Contact',r:[['Submitted By',(D.filler_name||'')+(D.filler_role?' ('+D.filler_role+')':'')],['Filler Email',D.filler_email],['Primary Contact',(D.contact_name||'')+(D.contact_role?' ('+D.contact_role+')':'')],['Email',D.contact_email],['Phone',D.contact_phone],['Treasurer',D.treasurer_name]]},
    {t:'Bio & Messaging',r:[['Tagline',D.tagline],['Intro',D.homepage_intro],['Why Running',D.why_running]]},
    {t:'Background',r:[['Profession',D.profession],['Education',D.education_1]]},
    {t:'Branding',r:[['Headshot',D.headshot],['Logo',D.logo],['Primary Color',D.color_primary],['Style',D.visual_style]]},
    {t:'Compliance',r:[['Committee',D.committee_name],['Disclaimer',D.paidfor_text],['Privacy Email',D.privacy_contact_email]]},
    {t:'Fundraising',r:[['Donation',D.donation_needed],['Platform',D.donation_platform],['URL',D.donation_url]]},
    {t:'Survey',r:[['Survey Page',D.survey_page_wanted],['Focus',D.primary_survey_focus]]},
    {t:'Domain',r:[['Status',D.domain_status],['Preferred',D.domain_preferred]]},
    {t:'Timeline',r:[['Launch',D.launch_timeline],['Comms',D.comm_pref],['Approver',D.approver_name]]},
  ];
  document.getElementById('atpSB').innerHTML=secs.map(s=>{
    const rows=s.r.filter(([,v])=>v&&String(v).trim());if(!rows.length)return'';
    return`<div class="ass"><div class="ast2">${s.t}</div><div class="asg">${rows.map(([l,v])=>`<div class="asi"><div class="asl">${l}</div><div class="asv">${v}</div></div>`).join('')}</div></div>`;
  }).join('');
  document.getElementById('atpJ').textContent=JSON.stringify(buildV3(),null,2);
}

function SW(){
  const st=document.getElementById('atpSS');st.textContent='Saving...';
  const v3=buildV3();
  const fd=new FormData();fd.append('action','atp_save');fd.append('nonce',NC);fd.append('data',JSON.stringify(D));fd.append('v3_json',JSON.stringify(v3));
  fetch(AJ,{method:'POST',body:fd}).then(r=>r.json()).then(r=>{
    st.textContent=r.success?'Saved — '+r.data.name+' (ID '+r.data.post_id+')':'Error: '+(r.data||'?');
    if(r.success)localStorage.removeItem(LS);
  }).catch(()=>{st.textContent='Save failed.';});
}

window.AD=function(){
  const v3=buildV3();
  const n=(D.display_name||D.legal_name||'candidate').toLowerCase().replace(/\s+/g,'-');
  const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(v3,null,2)],{type:'application/json'}));
  a.download=n+'-atp-intake-v3.json';a.click();URL.revokeObjectURL(a.href);
};
window.AC=function(){
  const v3=buildV3();
  navigator.clipboard.writeText(JSON.stringify(v3,null,2)).then(()=>{
    const b=document.getElementById('atpCB');b.textContent='Copied';setTimeout(()=>b.textContent='Copy JSON',2500);
  });
};
window.AR=function(){
  Object.keys(D).forEach(k=>delete D[k]);Object.keys(CK).forEach(k=>delete CK[k]);
  localStorage.removeItem(LS);
  document.querySelectorAll('#atpr input,#atpr textarea').forEach(e=>e.value='');
  document.querySelectorAll('#atpr select').forEach(e=>e.selectedIndex=0);
  document.querySelectorAll('.ac.sel,.aki.chk').forEach(e=>e.classList.remove('sel','chk'));
  AG(1);
};

document.addEventListener('keydown',e=>{
  if(e.key==='Enter'&&!e.shiftKey&&cur<=TOT){
    if(document.activeElement.tagName==='TEXTAREA')return;
    const b=document.querySelector('#as'+cur+' .abt');if(b)b.click();
  }
});

// Background
(function(){
  const cv=document.getElementById('atpc'),ctx=cv.getContext('2d'),rt=document.getElementById('atpr');
  let W,H,t=0,sT=0,POINTS=120,rH=[],bH=[],rV=48,tR=48;
  let s=48;for(let i=0;i<POINTS;i++){s=Math.max(41,Math.min(59,s+(Math.random()-.48)*1.2));rH.push(s);bH.push(100-s);}
  function rsz(){W=cv.width=rt.offsetWidth;H=cv.height=rt.offsetHeight;}
  rsz();new ResizeObserver(rsz).observe(rt);
  function gY(v){const cT=H*.78,cH=H*.18;return cT+cH-((v-40)/22)*cH;}
  function drw(){
    const len=rH.length,cT=H*.78,cH=H*.18,cB=cT+cH,mY=gY(50);
    ctx.beginPath();ctx.moveTo(0,mY);ctx.lineTo(W,mY);ctx.strokeStyle='rgba(255,255,255,.05)';ctx.lineWidth=1;ctx.setLineDash([6,10]);ctx.stroke();ctx.setLineDash([]);
    [[rH,'rgba(212,43,43,.15)','rgba(212,43,43,0)'],[bH,'rgba(58,95,217,.15)','rgba(58,95,217,0)']].forEach(([arr,c0,c1])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=gY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y);});
      ctx.lineTo(W,cB);ctx.lineTo(0,cB);ctx.closePath();
      const g=ctx.createLinearGradient(0,cT,0,cB);g.addColorStop(0,c0);g.addColorStop(1,c1);ctx.fillStyle=g;ctx.fill();
    });
    [[rH,'rgba(212,43,43,.85)','rgba(212,43,43,.5)'],[bH,'rgba(58,95,217,.85)','rgba(58,95,217,.5)']].forEach(([arr,sc,sh])=>{
      ctx.beginPath();arr.forEach((v,i)=>{const x=(i/(len-1))*W,y=gY(v);i===0?ctx.moveTo(x,y):ctx.lineTo(x,y);});
      ctx.strokeStyle=sc;ctx.lineWidth=2;ctx.shadowColor=sh;ctx.shadowBlur=8;ctx.stroke();ctx.shadowBlur=0;
    });
    const p=(Math.sin(t*4)+1)/2;
    [[rH,'rgba(212,43,43,.9)','rgba(212,43,43,.7)'],[bH,'rgba(91,125,232,.9)','rgba(91,125,232,.7)']].forEach(([arr,fc,sc])=>{
      const y=gY(arr[arr.length-1]);ctx.beginPath();ctx.arc(W-2,y,3+p*2,0,Math.PI*2);ctx.fillStyle=fc;ctx.shadowColor=sc;ctx.shadowBlur=12;ctx.fill();ctx.shadowBlur=0;
    });
    const fg=ctx.createLinearGradient(0,cT-80,0,cT+20);fg.addColorStop(0,'rgba(14,18,53,1)');fg.addColorStop(1,'rgba(14,18,53,0)');ctx.fillStyle=fg;ctx.fillRect(0,cT-80,W,100);
  }
  function frame(){
    ctx.clearRect(0,0,W,H);drw();rV+=(tR-rV)*.02;sT++;
    if(sT>60){tR=Math.max(41,Math.min(59,tR+(Math.random()-.48)*1.8));sT=0;}
    if(Math.round(t*60)%120===0){rH.push(rV);bH.push(100-rV);if(rH.length>POINTS){rH.shift();bH.shift();}}
    const bar=document.getElementById('atpRR');if(bar)bar.style.width=rV+'%';
    t+=.016;requestAnimationFrame(frame);
  }
  frame();
})();

// Bind file-upload event listeners via addEventListener instead of inline
// attributes. Inline onchange/ondrop attributes execute in the global scope,
// so they break under (a) IIFE-scoped function declarations, (b) JS minifiers
// that rename identifiers, and (c) CSPs that block inline event handlers.
// addEventListener avoids all three.
(function atpInitUploads(){
  function bind(){
    var zones=document.querySelectorAll('[data-atp-zone]');
    atpLog('init: found',zones.length,'upload zone(s)');
    zones.forEach(function(z){
      var fid=z.getAttribute('data-atp-zone');
      var input=document.querySelector('[data-atp-file="'+fid+'"]');
      if(!input){atpLog('init: no input for',fid);return;}
      z.addEventListener('click',function(){input.click();});
      z.addEventListener('dragover',function(e){e.preventDefault();z.classList.add('drag');});
      z.addEventListener('dragleave',function(){z.classList.remove('drag');});
      z.addEventListener('drop',function(e){e.preventDefault();z.classList.remove('drag');atpHandleDrop(e,fid);});
      input.addEventListener('change',function(){atpLog('change',fid,'files=',input.files&&input.files.length);atpHandleFiles(input.files,fid);});
    });
  }
  if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',bind);}else{bind();}
})();
})();
</script>
<?php return ob_get_clean();}
