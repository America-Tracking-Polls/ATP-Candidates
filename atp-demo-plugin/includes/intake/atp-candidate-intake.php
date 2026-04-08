<?php
/**
 * Plugin Name:  ATP Candidate Intake
 * Plugin URI:   https://americatrackingpolls.com
 * Description:  America Tracking Polls — candidate intake form with settings, question editor, branding, email notifications, and admin backend.
 * Version:      2.0.0
 * Author:       Mirror Factory / ROI Amplified
 * Text Domain:  atp-intake
 */
if(!defined('ABSPATH'))exit;

/* ════════════════════════════════════════
   DEFAULT QUESTIONS
════════════════════════════════════════ */
function atp_default_questions(){return[
['id'=>'q1','section'=>'01 — Candidate Identity','question'=>"What is the candidate's name?",'subtitle'=>'','fields'=>[
    ['id'=>'legal_name','label'=>'Full legal name','type'=>'text','placeholder'=>'e.g. Jonathan R. Stacy','optional'=>false],
    ['id'=>'display_name','label'=>'Display name on site','type'=>'text','placeholder'=>'e.g. John Stacy','optional'=>false],
]],
['id'=>'q2','section'=>'02 — The Race','question'=>'What office and where?','subtitle'=>'','fields'=>[
    ['id'=>'office','label'=>'Office sought','type'=>'text','placeholder'=>'e.g. County Commissioner','optional'=>false],
    ['id'=>'district','label'=>'District / County / Jurisdiction','type'=>'text','placeholder'=>'e.g. Precinct 4, Rockwall County','optional'=>false],
    ['id'=>'state','label'=>'State','type'=>'text','placeholder'=>'Texas','optional'=>false],
    ['id'=>'party','label'=>'Party','type'=>'text','placeholder'=>'Republican / Democrat / Nonpartisan','optional'=>false],
    ['id'=>'election_date','label'=>'Election date','type'=>'date','placeholder'=>'','optional'=>false],
    ['id'=>'election_type','label'=>'Election type','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Primary','General','Runoff','Special election']],
]],
['id'=>'q3','section'=>'03 — Race Position','question'=>'Incumbent, challenger, or open seat?','subtitle'=>'','fields'=>[
    ['id'=>'position','label'=>'','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Incumbent','Challenger','Open seat — no incumbent']],
    ['id'=>'opponents','label'=>'Known opponents','type'=>'text','placeholder'=>'Names separated by commas','optional'=>true],
]],
['id'=>'q4','section'=>'04 — Campaign Contact','question'=>'Who do we communicate with?','subtitle'=>'','fields'=>[
    ['id'=>'contact_name','label'=>'Contact name','type'=>'text','placeholder'=>'Full name','optional'=>false],
    ['id'=>'contact_role','label'=>'Role','type'=>'text','placeholder'=>'Campaign Manager, Candidate...','optional'=>false],
    ['id'=>'contact_email','label'=>'Email','type'=>'email','placeholder'=>'campaign@email.com','optional'=>false],
    ['id'=>'contact_phone','label'=>'Phone','type'=>'tel','placeholder'=>'(000) 000-0000','optional'=>false],
    ['id'=>'address','label'=>'Mailing address','type'=>'text','placeholder'=>'Campaign mailing address','optional'=>true],
    ['id'=>'existing_website','label'=>'Existing website','type'=>'url','placeholder'=>'https://','optional'=>true],
]],
['id'=>'q5','section'=>'05 — Bio & Messaging','question'=>'Tell us about the candidate','subtitle'=>'','fields'=>[
    ['id'=>'bio_short','label'=>'Short bio — 2 to 3 sentences (hero section)','type'=>'textarea','placeholder'=>"Who they are and why they're running...",'optional'=>false],
    ['id'=>'bio_full','label'=>'Full bio','type'=>'textarea','placeholder'=>'Full background, career, family, community...','optional'=>true],
    ['id'=>'tagline','label'=>'Campaign tagline or slogan','type'=>'text','placeholder'=>'e.g. Proven Leadership. Real Results.','optional'=>true],
    ['id'=>'differentiator','label'=>'What makes this candidate different?','type'=>'textarea','placeholder'=>'1–2 sentences...','optional'=>false],
]],
['id'=>'q6','section'=>'06 — Platform','question'=>'Top issues and platform priorities','subtitle'=>'List 3–5 most important issues, one per line.','fields'=>[
    ['id'=>'issues','label'=>'','type'=>'textarea','placeholder'=>"1. Public safety\n2. Property taxes\n3. Infrastructure",'optional'=>false],
    ['id'=>'target_voter','label'=>'Target voter — who are you trying to reach?','type'=>'textarea','placeholder'=>'e.g. Suburban homeowners, rural conservatives...','optional'=>false],
]],
['id'=>'q7','section'=>'07 — Visual Branding','question'=>'Existing brand assets','subtitle'=>'Share what you have. We use the ATP template for anything missing.','fields'=>[
    ['id'=>'colors','label'=>'Brand colors','type'=>'text','placeholder'=>'e.g. Navy #002868, Red #BF0A30 — or use template','optional'=>true],
    ['id'=>'fonts','label'=>'Preferred fonts','type'=>'text','placeholder'=>'e.g. Montserrat — or leave blank for template','optional'=>true],
    ['id'=>'logo_url','label'=>'Logo — share link or note to send separately','type'=>'url','placeholder'=>'Google Drive / Dropbox link','optional'=>true],
    ['id'=>'photos_url','label'=>'Headshot and photos (minimum 1 needed)','type'=>'url','placeholder'=>'Google Drive / Dropbox link','optional'=>false],
    ['id'=>'other_assets','label'=>'Other design materials to match','type'=>'text','placeholder'=>'Yard signs, mailers, existing graphics...','optional'=>true],
    ['id'=>'tone','label'=>'Overall aesthetic preference','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Professional / polished','Bold / aggressive','Grassroots / approachable','Clean / minimal','Traditional / patriotic','Modern / tech-forward','No preference — use template']],
]],
['id'=>'q8','section'=>'08 — Video','question'=>'Do you have video content?','subtitle'=>'','fields'=>[
    ['id'=>'video_main','label'=>'Main campaign video (YouTube / Vimeo URL)','type'=>'url','placeholder'=>'https://youtube.com/...','optional'=>true],
    ['id'=>'video_other','label'=>'Other video assets','type'=>'textarea','placeholder'=>'Ads, testimonials, event clips. Include links.','optional'=>true],
]],
['id'=>'q9','section'=>'09 — Social Media','question'=>'Campaign social profiles','subtitle'=>'Share only what exists. Leave blank if not applicable.','fields'=>[
    ['id'=>'facebook','label'=>'Facebook','type'=>'url','placeholder'=>'https://facebook.com/...','optional'=>true],
    ['id'=>'twitter_x','label'=>'X / Twitter','type'=>'url','placeholder'=>'https://x.com/...','optional'=>true],
    ['id'=>'instagram','label'=>'Instagram','type'=>'url','placeholder'=>'https://instagram.com/...','optional'=>true],
    ['id'=>'youtube','label'=>'YouTube','type'=>'url','placeholder'=>'https://youtube.com/...','optional'=>true],
    ['id'=>'tiktok','label'=>'TikTok','type'=>'url','placeholder'=>'https://tiktok.com/...','optional'=>true],
    ['id'=>'linkedin','label'=>'LinkedIn','type'=>'url','placeholder'=>'https://linkedin.com/...','optional'=>true],
    ['id'=>'social_other','label'=>'Other platform','type'=>'url','placeholder'=>'Any other platform URL','optional'=>true],
]],
['id'=>'q10','section'=>'10 — Survey Package','question'=>'Which survey sequence?','subtitle'=>'','fields'=>[
    ['id'=>'survey_tier','label'=>'','type'=>'radio','placeholder'=>'','optional'=>false,'options'=>['Tier 1 — Benchmark: Name ID + favorability baseline. One survey.','Tier 2 — Benchmark + Tracking: Baseline + approval improvement. Two surveys.','Tier 3 — Full Sequence: Benchmark → approval → undecided targeting + phone outreach.']],
]],
['id'=>'q11','section'=>'11 — Survey Delivery','question'=>'How should the survey reach voters?','subtitle'=>'Select all that apply.','fields'=>[
    ['id'=>'channels','label'=>'','type'=>'checkbox','placeholder'=>'','optional'=>true,'options'=>['MMS / Text — ~50% reach, cell numbers only','Website embed','QR-coded print media','Digital ads','Social media']],
    ['id'=>'voter_file','label'=>'Voter file or contact list?','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['Yes — will upload separately','No — need help sourcing','In progress']],
    ['id'=>'matrix_figures','label'=>'Favorability matrix comparison figures','type'=>'text','placeholder'=>'Default: Trump, Newsom, State Governor, one Senator, candidate + opponents','optional'=>true],
]],
['id'=>'q12','section'=>'12 — Legal & Compliance','question'=>'Campaign legal details','subtitle'=>'','fields'=>[
    ['id'=>'disclaimer','label'=>'Paid-for-by disclaimer (exact legal text required)','type'=>'text','placeholder'=>'e.g. Paid for by John Stacy for Commissioner','optional'=>false],
    ['id'=>'fec_id','label'=>'FEC or state campaign finance ID','type'=>'text','placeholder'=>'Campaign ID number','optional'=>true],
    ['id'=>'treasurer','label'=>'Treasurer name (for footer)','type'=>'text','placeholder'=>'Treasurer full name','optional'=>true],
    ['id'=>'tcpa','label'=>'TCPA / 10DLC compliance status (required for SMS)','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — fully compliant','In progress','Not yet — need help','Not using SMS']],
    ['id'=>'privacy_email','label'=>'Privacy policy contact email','type'=>'email','placeholder'=>'Can match campaign email','optional'=>false],
    ['id'=>'legal_notes','label'=>'State-specific legal requirements','type'=>'textarea','placeholder'=>'State-specific disclosures, compliance rules...','optional'=>true],
]],
['id'=>'q13','section'=>'13 — Fundraising','question'=>'Donation and fundraising setup','subtitle'=>'','fields'=>[
    ['id'=>'donation_needed','label'=>'Do you need a donation page?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — build one','No','Already have one — just link it']],
    ['id'=>'donation_platform','label'=>'Fundraising platform','type'=>'select','placeholder'=>'','optional'=>true,'options'=>['ActBlue','WinRed','Anedot','PayPal','Other','Not using one']],
    ['id'=>'donation_url','label'=>'Existing donation link','type'=>'url','placeholder'=>'https://actblue.com/...','optional'=>true],
    ['id'=>'fundraising_message','label'=>'Fundraising goal or call-to-action copy','type'=>'text','placeholder'=>'e.g. Help us reach $10,000 by primary day','optional'=>true],
]],
['id'=>'q14','section'=>'14 — Site Pages','question'=>'Which pages do you need?','subtitle'=>'Home, Privacy Policy, Terms, SMS Consent, and Survey page are included. Select any additional pages below.','fields'=>[
    ['id'=>'pages_additional','label'=>'','type'=>'checkbox','placeholder'=>'','optional'=>true,'options'=>['About the Candidate','Issues / Platform','Endorsements','Volunteer Sign-Up','Events / Appearances Calendar','Press / News / Blog','Contact Page','Media / Press Kit']],
]],
['id'=>'q15','section'=>'15 — Endorsements','question'=>'Any endorsements to feature?','subtitle'=>'Names, titles, organizations. Include quotes if available.','fields'=>[
    ['id'=>'endorsements','label'=>'','type'=>'textarea','placeholder'=>"e.g.\nSheriff Mike Brown — 'John has my full support'\nRockwall County Republican Party",'optional'=>true],
]],
['id'=>'q16','section'=>'16 — Domain & Technical','question'=>'Domain and technical setup','subtitle'=>'','fields'=>[
    ['id'=>'domain_status','label'=>'Do you have a domain?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Yes — I own it already','No — need one purchased','Not sure']],
    ['id'=>'domain_preferred','label'=>'Preferred domain name','type'=>'text','placeholder'=>'e.g. johnstacyforcommissioner.com','optional'=>true],
    ['id'=>'tracking_ids','label'=>'Google Analytics or Facebook Pixel IDs','type'=>'text','placeholder'=>'GA-XXXXXXXX, Pixel ID...','optional'=>true],
    ['id'=>'email_platform','label'=>'Email list or CRM platform','type'=>'text','placeholder'=>'e.g. Mailchimp, Constant Contact, none','optional'=>true],
    ['id'=>'other_integrations','label'=>'Other integrations needed','type'=>'textarea','placeholder'=>'Forms, CRMs, automation tools...','optional'=>true],
]],
['id'=>'q17','section'=>'17 — Custom Requests','question'=>'Anything specific not covered yet?','subtitle'=>'Items outside the standard package may incur additional cost.','fields'=>[
    ['id'=>'custom_requests','label'=>'','type'=>'textarea','placeholder'=>'Describe any custom requests...','optional'=>true],
    ['id'=>'approver','label'=>'Who provides final content approval?','type'=>'text','placeholder'=>'Name and role of whoever signs off','optional'=>false],
    ['id'=>'copy_help','label'=>'Will you provide copy or need writing help?','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['We will provide all copy','We need help writing some copy','We need full content writing assistance']],
]],
['id'=>'q18','section'=>'18 — Timeline','question'=>'When do you need the site live?','subtitle'=>'','fields'=>[
    ['id'=>'launch_date','label'=>'Target launch date','type'=>'date','placeholder'=>'','optional'=>false],
    ['id'=>'comm_pref','label'=>'Preferred communication method','type'=>'select','placeholder'=>'','optional'=>false,'options'=>['Email','Phone','Text','All of the above']],
    ['id'=>'final_notes','label'=>'Anything else we should know?','type'=>'textarea','placeholder'=>'Final notes, questions, context...','optional'=>true],
]],
];}

function atp_get_settings(){
    return wp_parse_args(get_option('atp_settings',[]),[
        'logo_text'=>'America <span>Tracking</span> Polls',
        'logo_image'=>'',
        'notify_emails'=>[],
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
    $raw=get_post_meta($id);$data=[];foreach($raw as $k=>$v){$data[$k]=maybe_unserialize($v[0]);}
    $name=$data['display_name']??$data['legal_name']??'Candidate';
    $back=admin_url('admin.php?page=atp-candidates');
    $exp=wp_nonce_url(admin_url('admin-ajax.php?action=atp_export_single&id='.$id),'atp_export','nonce');
    $groups=['Candidate & Race'=>['legal_name','display_name','office','district','state','party','election_date','election_type','position','opponents'],'Contact'=>['contact_name','contact_role','contact_email','contact_phone','address','existing_website'],'Bio & Messaging'=>['bio_short','bio_full','tagline','differentiator'],'Platform'=>['issues','target_voter'],'Branding'=>['colors','fonts','logo_url','photos_url','other_assets','tone'],'Video'=>['video_main','video_other'],'Social Media'=>['facebook','twitter_x','instagram','youtube','tiktok','linkedin','social_other'],'Survey'=>['survey_tier','channels','voter_file','matrix_figures'],'Compliance'=>['disclaimer','fec_id','treasurer','tcpa','privacy_email','legal_notes'],'Fundraising'=>['donation_needed','donation_platform','donation_url','fundraising_message'],'Pages'=>['pages_standard','pages_additional'],'Endorsements'=>['endorsements'],'Domain & Technical'=>['domain_status','domain_preferred','tracking_ids','email_platform','other_integrations'],'Custom Requests'=>['custom_requests','approver','copy_help'],'Timeline'=>['launch_date','comm_pref','final_notes']];
    echo '<div class="wrap"><h1>'.esc_html($name).' <a href="'.esc_url($back).'" class="button" style="margin-left:16px;font-size:12px">← All</a> <a href="'.esc_url($exp).'" class="button button-primary" style="margin-left:8px">Download JSON</a></h1>';
    foreach($groups as $g=>$fields){
        $rows='';foreach($fields as $f){$v=$data[$f]??null;if(!$v)continue;if(is_array($v))$v=implode(', ',$v);$l=ucwords(str_replace('_',' ',$f));$rows.='<tr><th style="width:200px;text-align:left;padding:8px 12px;color:#666;font-weight:500">'.esc_html($l).'</th><td style="padding:8px 12px">'.nl2br(esc_html($v)).'</td></tr>';}
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
    foreach($raw as $k=>$v){$k=sanitize_key($k);is_array($v)?update_post_meta($pid,$k,array_map('sanitize_textarea_field',$v)):update_post_meta($pid,$k,sanitize_textarea_field((string)$v));}
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
    $pairs=[['Candidate',$name],['Office',$office],['State',$state],['Party',$data['party']??'—'],['Election',($data['election_type']??'').($data['election_date']?' — '.$data['election_date']:'')],['Survey Tier',$data['survey_tier']??'—'],['Contact Email',$data['contact_email']??'—'],['Launch Date',$data['launch_date']??'—'],['Survey Channels',is_array($data['channels']??null)?implode(', ',$data['channels']):'—'],['Disclaimer',$data['disclaimer']??'—']];
    $rows='';foreach($pairs as[$l,$v]){if(!$v||$v==='—')continue;$rows.='<tr><td style="padding:8px 12px;border:1px solid #eee;background:#fff;color:#666;width:140px;font-size:13px">'.esc_html($l).'</td><td style="padding:8px 12px;border:1px solid #eee;font-size:13px;font-weight:600">'.esc_html($v).'</td></tr>';}
    $body='<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;max-width:640px;margin:0 auto;padding:20px">';
    $body.='<div style="background:#0e1235;padding:16px 24px;border-radius:6px 6px 0 0;display:flex;align-items:center;gap:12px">';
    $body.='<div style="background:#d42b2b;width:28px;height:28px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;color:white;font-size:16px">★</div>';
    $body.='<span style="color:white;font-size:13px;font-weight:700;letter-spacing:2px;text-transform:uppercase">AMERICA TRACKING POLLS</span></div>';
    $body.='<div style="background:#f9f9f9;border:1px solid #ddd;border-top:none;padding:24px;border-radius:0 0 6px 6px">';
    $body.='<h2 style="color:#0e1235;margin:0 0 16px;font-size:18px">New Candidate Intake Submission</h2>';
    $body.='<table style="width:100%;border-collapse:collapse;margin-bottom:20px">'.$rows.'</table>';
    $body.='<a href="'.esc_url($view).'" style="display:inline-block;background:#d42b2b;color:white;padding:12px 24px;border-radius:4px;text-decoration:none;font-weight:700;font-size:13px;letter-spacing:1px;text-transform:uppercase;margin-bottom:24px">View Full Submission →</a>';
    $body.='<h3 style="color:#666;font-size:12px;text-transform:uppercase;letter-spacing:1px;border-top:1px solid #eee;padding-top:16px;margin:0 0 8px">Full JSON Data</h3>';
    $body.='<pre style="background:#0e1235;color:#7eb8f7;padding:16px;border-radius:4px;font-size:11px;white-space:pre-wrap;word-break:break-all">'.json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE).'</pre>';
    $body.='</div></body></html>';
    $h=['Content-Type: text/html; charset=UTF-8'];
    foreach($emails as $e){if(is_email(trim($e)))wp_mail(trim($e),$subj,$body,$h);}
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
.ach{display:flex;flex-direction:column;gap:9px}
.ac{display:flex;align-items:flex-start;gap:13px;padding:14px 17px;border:1px solid var(--w2);border-radius:6px;cursor:pointer;background:var(--w1);transition:border-color .2s,background .2s;user-select:none}
.ac:hover{border-color:rgba(212,43,43,.5);background:rgba(212,43,43,.06)}
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
    <div class="afg">
    <?php
    $fields=$q['fields'];$fi=0;
    while($fi<count($fields)):
        $f=$fields[$fi];$tp=$f['type'];$fid=esc_attr($f['id']);$lbl=esc_html($f['label']);$ph=esc_attr($f['placeholder']??'');$opt=!empty($f['optional']);$opts=$f['options']??[];
        if($tp==='radio'):?>
      <div class="ach" id="ac_<?=$fid?>">
        <?php foreach($opts as $oi=>$o):$pts=explode(':',$o,2);?>
        <div class="ac" onclick="AP('<?=$fid?>','<?=esc_js(trim($pts[0]))?>',this)">
          <div class="ack"><?=$keys[$oi]??($oi+1)?></div>
          <div><div class="acl"><?=esc_html(trim($pts[0]))?></div><?php if(!empty($pts[1])):?><div class="acn"><?=esc_html(trim($pts[1]))?></div><?php endif;?></div>
        </div>
        <?php endforeach;?>
      </div>
    <?php elseif($tp==='checkbox'):?>
      <div class="akg" id="ak_<?=$fid?>">
        <?php foreach($opts as $o):?>
        <div class="aki" onclick="AK(this)"><div class="akb"><svg viewBox="0 0 10 8" fill="none"><polyline points="1,4 4,7 9,1" stroke="white" stroke-width="1.5"/></svg></div><div class="acl"><?=esc_html($o)?></div></div>
        <?php endforeach;?>
      </div>
    <?php elseif($tp==='select'):?>
      <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
        <select id="<?=$fid?>"><option value="">Select</option><?php foreach($opts as $o):?><option><?=esc_html($o)?></option><?php endforeach;?></select>
      </div>
    <?php elseif($tp==='textarea'):?>
      <div class="afc"><?php if($lbl):?><label class="afl" for="<?=$fid?>"><?=$lbl?><?php if($opt):?><span class="ao">optional</span><?php endif;?></label><?php endif;?>
        <textarea id="<?=$fid?>" placeholder="<?=$ph?>"></textarea>
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
      <?php endif;endif;$fi++;endwhile;?>
    </div>
    <div class="acta">
      <?php if(!$first):?><button class="abk" onclick="AG(<?=$sn-1?>)">← Back</button><?php endif;?>
      <?php if(!$last):?><button class="abt" onclick="AG(<?=$sn+1?>)">Continue</button>
      <?php else:?><button class="abt" onclick="AF()">Generate Profile →</button><?php endif;?>
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
function vl(id){const e=document.getElementById(id);return e?e.value.trim():'';}
function ca(){
  document.querySelectorAll('#atpr input[type=text],#atpr input[type=email],#atpr input[type=date],#atpr input[type=url],#atpr input[type=tel],#atpr textarea,#atpr select').forEach(e=>{if(e.id&&e.value.trim())D[e.id]=e.value.trim();});
  document.querySelectorAll('#atpr .akg').forEach(g=>{const k=g.id.replace('ak_','');D[k]=CK[g.id]||[];});
  sv();
}

window.AG=function(n){ca();cur=n;
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
  el.classList.add('sel');D[k]=v;sv();
};

window.AK=function(el){
  el.classList.toggle('chk');
  const gid=el.closest('.akg').id;if(!CK[gid])CK[gid]=[];
  const t=el.querySelector('.acl').textContent.trim();
  if(el.classList.contains('chk')){if(!CK[gid].includes(t))CK[gid].push(t);}
  else{CK[gid]=CK[gid].filter(i=>i!==t);}sv();
};

window.AF=function(){
  ca();D.pages_standard=['Home','Privacy Policy','Terms of Use','SMS/TCPA Consent','Survey Page'];
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
    {t:'Candidate & Race',r:[['Name',D.legal_name],['Display',D.display_name],['Office',D.office],['District',D.district],['State',(D.state||'')+(D.party?' — '+D.party:'')],['Election',(D.election_type||'')+(D.election_date?' — '+D.election_date:'')],['Position',D.position]]},
    {t:'Contact',r:[['Name',(D.contact_name||'')+(D.contact_role?' ('+D.contact_role+')':'')],['Email',D.contact_email],['Phone',D.contact_phone]]},
    {t:'Branding',r:[['Colors',D.colors],['Logo',D.logo_url],['Photos',D.photos_url],['Tone',D.tone]]},
    {t:'Survey',r:[['Tier',D.survey_tier],['Channels',Array.isArray(D.channels)?D.channels.join(', '):D.channels],['Disclaimer',D.disclaimer]]},
    {t:'Fundraising',r:[['Donation',D.donation_needed],['Platform',D.donation_platform],['URL',D.donation_url]]},
    {t:'Timeline',r:[['Launch',D.launch_date],['Comms',D.comm_pref]]},
  ];
  document.getElementById('atpSB').innerHTML=secs.map(s=>{
    const rows=s.r.filter(([,v])=>v&&String(v).trim());if(!rows.length)return'';
    return`<div class="ass"><div class="ast2">${s.t}</div><div class="asg">${rows.map(([l,v])=>`<div class="asi"><div class="asl">${l}</div><div class="asv">${v}</div></div>`).join('')}</div></div>`;
  }).join('');
  document.getElementById('atpJ').textContent=JSON.stringify(D,null,2);
}

function SW(){
  const st=document.getElementById('atpSS');st.textContent='Saving...';
  const fd=new FormData();fd.append('action','atp_save');fd.append('nonce',NC);fd.append('data',JSON.stringify(D));
  fetch(AJ,{method:'POST',body:fd}).then(r=>r.json()).then(r=>{
    st.textContent=r.success?'Saved — '+r.data.name+' (ID '+r.data.post_id+')':'Error: '+(r.data||'?');
    if(r.success)localStorage.removeItem(LS);
  }).catch(()=>{st.textContent='Save failed.';});
}

window.AD=function(){
  const n=(D.display_name||D.legal_name||'candidate').toLowerCase().replace(/\s+/g,'-');
  const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(D,null,2)],{type:'application/json'}));
  a.download=n+'-atp-intake.json';a.click();URL.revokeObjectURL(a.href);
};
window.AC=function(){
  navigator.clipboard.writeText(JSON.stringify(D,null,2)).then(()=>{
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
})();
</script>
<?php return ob_get_clean();}
