<?php 
/*
 * Copyright 2010 Sean Proctor
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if ( !defined('IN_PHPC') ) {
       die("Hacking attempt");
}

function settings()
{
	global $phpcdb, $phpc_user;

	if(is_user()) {
		$forms = array();

		if($phpc_user->is_password_editable())
			$forms[] = password_form();
		$forms[] = config_form();
		return $forms;
	} else {
		return config_form();
	}
}

function password_form()
{
	global $phpc_script;

	return tag('form', attributes("action=\"$phpc_script\"",
                                'method="post"'),
			tag('table', attributes("class=\"phpc-container\""),
				tag('caption', _('Change Password')),
				tag('tfoot',
					tag('tr',
						tag('td', attributes('colspan="2"'),
							create_hidden('phpc_token', $_SESSION['phpc_token']),
							create_hidden('action', 'password_submit'),
							create_submit(_('Submit'))))),
				tag('tbody',
					tag('tr',
						tag('th', _('Old Password')),
						tag('td', create_password('old_password'))),
					tag('tr',
						tag('th', _('New Password')),
						tag('td', create_password('password1'))),
					tag('tr',
						tag('th', _('Confirm New Password')),
						tag('td', create_password('password2')))
				   )));
}

function config_form()
{
	global $phpc_script, $phpc_user_tz, $phpc_user_lang;

	$timezones = array("NULL" => _("Default"));
	foreach(timezone_identifiers_list() as $timezone) {
		$timezones[$timezone] = $timezone;
	}
	$tz_input = create_select('timezone', $timezones,
			$phpc_user_tz);

	$languages = array("NULL" => _("Default"));
	foreach(get_languages() as $lang) {
		$languages[$lang] = $lang;
	}
	$lang_input = create_select('language', $languages,
			$phpc_user_lang);

	return tag('form', attributes("action=\"$phpc_script\"",
				'method="post"'),
			tag('table', attributes("class=\"phpc-container\""),
				tag('caption', _('Timezone')),
				tag('tfoot',
					tag('tr',
						tag('td', attributes('colspan="2"'),
							create_hidden('phpc_token', $_SESSION['phpc_token']),
							create_hidden('action', 'settings_submit'),
							create_submit(_('Submit'))))),
				tag('tbody',
					tag('tr',
						tag('th', _('Timezone')),
						tag('td', $tz_input)),
					tag('tr',
						tag('th', _('Language')),
						tag('td', $lang_input))
				   )));
}

?>
