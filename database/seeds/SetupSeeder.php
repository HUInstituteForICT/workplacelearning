<?php

use App\Category;
use App\EducationProgram;
use App\EducationProgramType;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
{
    private $db;

    /**
     * Run the database seeds.
     *
     * @param DatabaseManager $db
     * @return void
     */
    public function run(DatabaseManager $db)
    {
        $this->db = $db;

        // Start transaction to make sure only on success it all goes
        $this->db->transaction(function () {

            $this->db->statement("SET SESSION FOREIGN_KEY_CHECKS=0;");
            $this->db->statement("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';");


            // Execute the SQL queries to create a "default state" of this application
            collect([
                "
INSERT INTO `category` (`category_id`, `category_label`, `wplp_id`) VALUES
(1, 'Onderzoek doen', 0),
(2, 'Programmeren', 0),
(3, 'Testen', 0),
(4, 'Documenteren', 0),
(5, 'Overleg', 0),
(6, 'School', 0);
",

                "                
INSERT INTO `competence` (`competence_id`, `competence_label`, `educationprogram_id`) VALUES
(1, 'Interpersoonlijk', 2),
(2, 'Pedagogisch', 2),
(3, 'Vakinhoudelijk en (vak)didactisch', 2),
(4, 'Organisatorisch', 2),
(5, 'Samenwerken met collega’s', 2),
(6, 'Samenwerken met de omgeving', 2),
(7, 'Reflectie en ontwikkeling', 2),
(8, 'Onderzoekend handelen in de onderwijspraktijk', 2);
", "
INSERT INTO `difficulty` (`difficulty_id`, `difficulty_label`) VALUES
(1, 'Makkelijk'),
(2, 'Gemiddeld'),
(3, 'Moeilijk');
", "
INSERT INTO `educationprogram` (`ep_id`, `ep_name`, `eptype_id`) VALUES
(1, 'HBO ICT', 2),
(2, 'Lerarenopleiding Tweedegraads', 1);
", "
INSERT INTO `educationprogramtype` (`eptype_id`, `eptype_name`) VALUES
(1, 'Acting'),
(2, 'Producing');
", "
INSERT INTO `resourcematerial` (`rm_id`, `rm_label`, `wplp_id`) VALUES
(1, 'Internet', 0),
(2, 'Boek/Artikel', 0);
", "
INSERT INTO `resourceperson` (`rp_id`, `person_label`, `ep_id`, `wplp_id`) VALUES
(1, 'Stagebegeleider', 1, 0),
(2, 'Collega', 1, 0),
(3, 'Specialist', 1, 0),
(4, 'Medestagiair/Student', 1, 0),
(16, 'Stagebegeleider', 2, 0),
(17, 'Medestudent', 2, 0),
(18, 'Alleen', 2, 0),
(19, 'Begeleider HU', 2, 0);
", "
INSERT INTO `status` (`status_id`, `status_label`) VALUES
(1, 'Afgerond'),
(2, 'Mee Bezig'),
(3, 'Overgedragen');
", "
INSERT INTO `student` (`student_id`, `studentnr`, `firstname`, `lastname`, `ep_id`, `userlevel`, `pw_hash`, `gender`, `birthdate`, `email`, `phonenr`, `registrationdate`, `answer`) VALUES
(0, 0, 'Default', 'Student', 1, 0, '-', 'm', '2017-01-01', 'default.student@student.hu.nl', '0000000000', '2017-01-01 00:00:00', NULL);
", "
INSERT INTO `timeslot` (`timeslot_id`, `timeslot_text`, `edprog_id`) VALUES
(1, '1e lesuur', 2),
(2, '2e lesuur', 2),
(3, '3e lesuur', 2),
(4, '4e lesuur', 2),
(5, '5e lesuur', 2),
(6, '6e lesuur', 2),
(7, '7e lesuur', 2),
(8, '8e lesuur', 2),
(9, '9e lesuur', 2),
(10, '10e lesuur', 2),
(11, 'Op een ander moment', 2);
", "
INSERT INTO `workplace` (`wp_id`, `wp_name`, `street`, `housenr`, `postalcode`, `town`, `contact_name`, `contact_email`, `contact_phone`, `numberofemployees`) VALUES
(0, 'Hogeschool Utrecht', 'Daltonlaan', '200', '3584 BJ', 'Utrecht', 'Esther van der Stappen', 'esther.vanderstappen@hu.nl', '0884818283', 1000);
", "
INSERT INTO `workplacelearningperiod` (`wplp_id`, `student_id`, `wp_id`, `startdate`, `enddate`, `nrofdays`, `description`) VALUES
(0, 0, 0, '2017-01-01', '2017-01-01', 0, 'Default workplace');
"
            ])->each(function($query) {
                $this->db->statement($query);
            });

        });

    }
}
