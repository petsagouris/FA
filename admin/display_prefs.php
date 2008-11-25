<?php
/**********************************************************************
    Copyright (C) 2008  FrontAccounting, LLC.
	Released under the terms of the GNU Affero General Public License,
	AGPL, as published by the Free Software Foundation, either version 
	3 of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/agpl-3.0.html>.
***********************************************************************/
$page_security =10;
$path_to_root="..";
include($path_to_root . "/includes/session.inc");

page(_("Display Setup"));

include_once($path_to_root . "/includes/date_functions.inc");
include_once($path_to_root . "/includes/ui.inc");

include_once($path_to_root . "/admin/db/company_db.inc");

//-------------------------------------------------------------------------------------------------

if (isset($_POST['setprefs'])) 
{
	if (!is_numeric($_POST['query_size']) || ($_POST['query_size']<1))
	{
		display_error($_POST['query_size']);
		display_error( _("Query size must integer and greater than zero."));
		set_focus('query_size');
	} else {
		$theme = user_theme();
		set_user_prefs($_POST['prices'], $_POST['Quantities'],
			$_POST['Rates'], $_POST['Percent'],
			check_value('show_gl'),
			check_value('show_codes'),
			$_POST['date_format'], $_POST['date_sep'],
			$_POST['tho_sep'], $_POST['dec_sep'],
			$_POST['theme'], $_POST['page_size'], check_value('show_hints'),
			$_POST['profile'], check_value('rep_popup'), (int)($_POST['query_size']), check_value('graphic_links'));

		language::set_language($_POST['language']);

		flush_dir($comp_path.'/'.user_company().'/js_cache');	

		if (user_theme() != $theme)
			reload_page("");

		display_notification_centered(_("Display settings have been updated."));
	}
}

start_form();
start_table($table_style2);

table_section_title(_("Decimal Places"));

text_row_ex(_("Prices/Amounts:"), 'prices', 5, 5, '', user_price_dec());
text_row_ex(_("Quantities:"), 'Quantities', 5, 5, '', user_qty_dec());
text_row_ex(_("Exchange Rates:"), 'Rates', 5, 5, '', user_exrate_dec());
text_row_ex(_("Percentages:"), 'Percent',  5, 5, '', user_percent_dec());

table_section_title(_("Dateformat and Separators"));

dateformats_list_row(_("Dateformat:"), "date_format", user_date_format());

dateseps_list_row(_("Date Separator:"), "date_sep", user_date_sep());

/* The array $dateseps is set up in config.php for modifications
possible separators can be added by modifying the array definition by editing that file */

thoseps_list_row(_("Thousand Separator:"), "tho_sep", user_tho_sep());

/* The array $thoseps is set up in config.php for modifications
possible separators can be added by modifying the array definition by editing that file */

decseps_list_row(_("Decimal Separator:"), "dec_sep", user_dec_sep());

/* The array $decseps is set up in config.php for modifications
possible separators can be added by modifying the array definition by editing that file */

table_section_title(_("Miscellaneous"));

check_row(_("Show hints for new users:"), 'show_hints', user_hints());

check_row(_("Show GL Information:"), 'show_gl', user_show_gl_info());

check_row(_("Show Item Codes:"), 'show_codes', user_show_codes());

themes_list_row(_("Theme:"), "theme", user_theme());

/* The array $themes is set up in config.php for modifications
possible separators can be added by modifying the array definition by editing that file */

pagesizes_list_row(_("Page Size:"), "page_size", user_pagesize());

/* The array $pagesizes is set up in config.php for modifications
possible separators can be added by modifying the array definition by editing that file */

if (!isset($_POST['profile']))
	$_POST['profile'] = user_print_profile();

print_profiles_list_row(_("Printing profile"). ':', 'profile', 
	null, _('Browser printing support'));

check_row(_("Use popup window to display reports:"), 'rep_popup', user_rep_popup(),
	false, _('Set this option to on if your browser directly supports pdf files'));

check_row(_("Use icons instead of text links:"), 'graphic_links', user_graphic_links(),
	false, _('Set this option to on for using icons instead of text links'));

text_row_ex(_("Query page size:"), 'query_size',  5, 5, '', user_query_size());

table_section_title(_("Language"));

if (!isset($_POST['language']))
	$_POST['language'] = $_SESSION['language']->code;

languages_list_row(_("Language:"), 'language', $_POST['language']);

end_table(1);

submit_center('setprefs', _("Update"), true, '', true);

end_form(2);

//-------------------------------------------------------------------------------------------------

end_page();

?>