<?php

use App\Category;
use App\Cohort;
use App\Competence;
use App\CompetenceDescription;
use App\ResourcePerson;
use App\Timeslot;
use App\WorkplaceLearningPeriod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCohortIdToModels extends Migration
{

    private $epToCohortMapping = [];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Same migrations for multiple models
         */

        $this->category();
        $this->competence();
        $this->competenceDescription();
        $this->resourcePerson();
        $this->timeslot();
        $this->wplp();


        // Loop over alle categories, krijg het EP_id, check of er mapping is ep_id => cohort_id, zoja zet cohort id, zo niet maak cohort en mapping
    }

    private function category()
    {
        if (!Schema::hasColumn('category', 'cohort_id')) {
            Schema::table('category', function (Blueprint $table) {

                $table->integer('ep_id')->nullable()->change();

                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Category_Cohort")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');


            });

        }

        $categories = Category::whereNotNull('ep_id')->whereNull('cohort_id')->with('educationProgram')->get();
        $categories->each(function (Category $category) {
            if (!isset($this->epToCohortMapping[$category->ep_id])) {
                $cohort = tap(new Cohort(["name"  => $category->educationProgram->ep_name,
                                          "ep_id" => $category->ep_id,
                ]))->save();
                $this->epToCohortMapping[$category->ep_id] = $cohort->id;
            }
            $category->cohort_id = $this->epToCohortMapping[$category->ep_id];
            $category->save();
        });
    }

    private function competence()
    {
        if (!Schema::hasColumn('competence', 'cohort_id')) {

            Schema::table('competence', function (Blueprint $table) {
                $table->integer('educationprogram_id')->nullable()->change();
                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Cohort_Competence")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');


            });

            $competence = Competence::whereNotNull('educationprogram_id')->whereNull('cohort_id')->with('educationProgram')->get();
            $competence->each(function (Competence $competence) {
                if (!isset($this->epToCohortMapping[$competence->educationprogram_id])) {
                    $cohort = tap(new Cohort(["name"  => $competence->educationProgram->ep_name,
                                              "ep_id" => $competence->educationprogram_id,
                    ]))->save();
                    $this->epToCohortMapping[$competence->educationprogram_id] = $cohort->id;
                }
                $competence->cohort_id = $this->epToCohortMapping[$competence->educationprogram_id];
                $competence->save();
            });
        }
    }

    private function competenceDescription()
    {
        if (!Schema::hasColumn('competence_descriptions', 'cohort_id')) {

            Schema::table('competence_descriptions', function (Blueprint $table) {
                $table->integer('education_program_id')->nullable()->change();
                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Cohort_CompetenceDescriptions")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');


            });

            $competenceDescription = CompetenceDescription::whereNotNull('education_program_id')->whereNull('cohort_id')->get();
            $competenceDescription->load('educationProgram');
            $competenceDescription->each(function (CompetenceDescription $competenceDescription) {
                if (!isset($this->epToCohortMapping[$competenceDescription->education_program_id])) {
                    $cohort = tap(new Cohort(["name"  => $competenceDescription->educationProgram->ep_name,
                                              "ep_id" => $competenceDescription->education_program_id,
                    ]))->save();
                    $this->epToCohortMapping[$competenceDescription->education_program_id] = $cohort->id;
                }
                $competenceDescription->cohort_id = $this->epToCohortMapping[$competenceDescription->education_program_id];
                $competenceDescription->save();
            });
        }
    }

    private function resourcePerson()
    {
        if (!Schema::hasColumn('resourceperson', 'cohort_id')) {
            Schema::table('resourceperson', function (Blueprint $table) {
                $table->integer('ep_id')->nullable()->change();
                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Cohort_ResourcePerson")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            });
        }

        $resourcePerson = ResourcePerson::whereNotNull('ep_id')->whereNull('cohort_id')->with('educationProgram')->get();
        $resourcePerson->each(function (ResourcePerson $resourcePerson) {
            if (!isset($this->epToCohortMapping[$resourcePerson->ep_id])) {
                $cohort = tap(new Cohort(["name"  => $resourcePerson->educationProgram->ep_name,
                                          "ep_id" => $resourcePerson->ep_id,
                ]))->save();
                $this->epToCohortMapping[$resourcePerson->ep_id] = $cohort->id;
            }
            $resourcePerson->cohort_id = $this->epToCohortMapping[$resourcePerson->ep_id];
            $resourcePerson->save();
        });
    }

    private function timeslot()
    {
        if (!Schema::hasColumn('timeslot', 'cohort_id')) {

            Schema::table('timeslot', function (Blueprint $table) {
                $table->integer('edprog_id')->nullable()->change();
                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Cohort_Timeslot")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            });
        }

        $timeslot = Timeslot::whereNotNull('edprog_id')->where('wplp_id', '0')->whereNull('cohort_id')->with('educationProgram')->get();
        $timeslot->each(function (Timeslot $timeslot) {
            if (!isset($this->epToCohortMapping[$timeslot->edprog_id])) {
                $cohort = tap(new Cohort(["name"  => $timeslot->educationProgram->ep_name,
                                          "ep_id" => $timeslot->edprog_id,
                ]))->save();
                $this->epToCohortMapping[$timeslot->edprog_id] = $cohort->id;
            }
            $timeslot->cohort_id = $this->epToCohortMapping[$timeslot->edprog_id];
            $timeslot->save();
        });
    }

    private function wplp()
    {
        if (!Schema::hasColumn('workplacelearningperiod', 'cohort_id')) {
            Schema::table('workplacelearningperiod', function (Blueprint $table) {
                $table->unsignedInteger("cohort_id")->nullable()->default(null);
                $table->foreign("cohort_id", "Fk_Cohort_Workplacelearningperiod")
                    ->references('id')->on('cohorts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            });
        }

        $wplp = WorkplaceLearningPeriod::whereNull('cohort_id')->with('student')->get();
        $wplp->each(function (WorkplaceLearningPeriod $workplaceLearningPeriod) {
            if (!isset($this->epToCohortMapping[$workplaceLearningPeriod->student->ep_id])) {
                $cohort = tap(new Cohort(["name"  => $workplaceLearningPeriod->student->educationProgram->ep_name,
                                          "ep_id" => $workplaceLearningPeriod->student->ed_id,
                ]))->save();
                $this->epToCohortMapping[$workplaceLearningPeriod->student->ep_id] = $cohort->id;
            }
            $workplaceLearningPeriod->cohort_id = $this->epToCohortMapping[$workplaceLearningPeriod->student->ep_id];
            $workplaceLearningPeriod->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Category_Cohort");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();

        });

        Schema::table('competence', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Cohort_Competence");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();

        });

        Schema::table('competence_descriptions', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Cohort_CompetenceDescriptions");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();

        });

        Schema::table('resourceperson', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Cohort_ResourcePerson");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();

        });

        Schema::table('timeslot', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Cohort_Timeslot");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();

        });

        Schema::table('workplacelearningperiod', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();

            $table->dropForeign("Fk_Cohort_Workplacelearningperiod");
            $table->dropColumn("cohort_id");

            Schema::enableForeignKeyConstraints();
        });

    }
}
