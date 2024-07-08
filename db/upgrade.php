<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * URL module upgrade code
 *
 * This file keeps track of upgrades to
 * the resource module
 *
 * Sometimes, changes between versions involve
 * alterations to database structures and other
 * major things that may break installations.
 *
 * The upgrade function in this file will attempt
 * to perform all the necessary actions to upgrade
 * your older installation to the current version.
 *
 * If there's something it cannot do itself, it
 * will tell you what you need to do.
 *
 * The commands in here will all be database-neutral,
 * using the methods of database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * File         upgrade.php
 * Encoding     UTF-8
 *
 * @package     enrol_fee
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade script for mod_gwpayments
 *
 * @param int $oldversion
 * @return boolean
 */
function xmldb_enrol_fee_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 3024070803) {

        // Define table enrol_fee to be created.
        $table = new xmldb_table('enrol_fee');

        // Adding fields to table enrol_fee.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('paymentid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table enrol_fee.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('paymentid', XMLDB_KEY_FOREIGN_UNIQUE, ['paymentid'], 'payments', ['id']);

        // Conditionally launch create table for enrol_fee.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Fee savepoint reached.
        upgrade_plugin_savepoint(true, 3024070803, 'enrol', 'fee');
    }

    return true;
}
