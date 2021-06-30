<?php


namespace App\Services\Factories;

use App\GenericLearningActivity;
use App\LearningGoal;
use App\Repository\Eloquent\GenericLearningActivityRepository;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Timeslot;
use App\Category;
use App\Chain;
use App\ChainManager;
use App\Reflection\Services\Factories\ActivityReflectionFactory;
use App\Services\CurrentUserResolver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GLAFactory
{
    /**
     * @var TimeslotFactory
     */
    private $timeslotFactory;
    /**
     * @var ResourcePersonFactory
     */
    private $resourcePersonFactory;
    /**
     * @var ResourceMaterialFactory
     */
    private $resourceMaterialFactory;
    /**
     * @var CurrentUserResolver
     */
    private $currentUserResolver;
    /**
     * @var GenericLearningActivityRepository
     */
    private $genericLearningActivityRepository;

    /**
     * @var ActivityReflectionFactory
     */
    private $activityReflectionFactory;

    /**
     * @var ChainManager
     */
    private $chainFactory;
    private $data;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    public function __construct(
        GenericLearningActivityRepository $genericLearningActivityRepository,
        TimeslotFactory $timeslotFactory,
        ResourcePersonFactory $resourcePersonFactory,
        ResourceMaterialFactory $resourceMaterialFactory,
        CurrentUserResolver $currentUserResolver,
        ActivityReflectionFactory $activityReflectionFactory,
        ChainFactory $chainFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->timeslotFactory = $timeslotFactory;
        $this->resourcePersonFactory = $resourcePersonFactory;
        $this->resourceMaterialFactory = $resourceMaterialFactory;
        $this->currentUserResolver = $currentUserResolver;
        $this->activityReflectionFactory = $activityReflectionFactory;
        $this->genericLearningActivityRepository = $genericLearningActivityRepository;
        $this->chainFactory = $chainFactory;
        $this->categoryFactory = $categoryFactory;
    }

    public function createGLA(Genericlearningactivity $genericlearningactivity, $data): bool{

        $genericLearningActivity = new GenericLearningActivity();
        $genericLearningActivity->learningActivity_name = $data['learningActivity_naam'];

        $this->data = $data;
        if ($data['resource'] === 'new') {
            $data['resource'] = 'other';
        }

        $category = $this->getCategory($data['category_id']);

        //Set relations
        $genericLearningActivity->workplaceLearningPeriod()->associate(Auth::user()->getCurrentWorkplaceLearningPeriod());
        $genericLearningActivity->category()->associate($category);
        $genericLearningActivity->timeslot()->associate($this->getTimeslot($data));
        $genericLearningActivity->learningGoal()->associate((new LearningGoal())::find($data['learning_goal']));

        // Needs to be after save because the relation is many-to-many, thus association table is used
        $genericLearningActivity->competence()->sync($data['competence']);

        //Based on old model with LAA & LAP instead of GLA
        /*$chainId = ((int) $data['chain_id']);
        if ($chainId !== -1) {
            $chain = Chain::find($data['chain_id']);
            $genericLearningActivity->chain()->associate($chain);
        } elseif ($chainId === -1 && $genericLearningActivity->status->isBusy()) {
            $chain = $this->chainFactory->createChain([
                'name'    => __('New chain').' - '.substr($genericLearningActivity->description, 0, 15),
                'wplp_id' => Auth::user()->getCurrentWorkplaceLearningPeriod(),
            ]);
            $genericLearningActivity->chain()->associate($chain);
        }*/

        //choosing fieldtypes from database
        $radiobutton = Fieldtype::where("fieldtype","radiobutton")->first();
        $text = Fieldtype::where("fieldtype","text")->first();
        $datePicker = Fieldtype::where("fieldtype","date")->first();
        $button = Fieldtype::where("fieldtype","date")->first();

        if (isset($data['datum'])) {
            $column = $this->createColumn("date", null, Carbon::parse($data['datum'])->format('Y-m-d'), $datePicker, "date");
            $genericlearningactivity->column()->associate($column);
        }
        if (isset($data['omschrijving'])) {
            $column = $this->createColumn("description", null, $data['omschrijving'], $text, "string");
            $genericlearningactivity->column()->associate($column);
        }
        if (isset($data['aantaluren']) || isset($data['aantaluren_custom'])) {
            $description = $data['aantaluren'] !== 'x' ? $data['aantaluren'] : round(
                ((int) $data['aantaluren_custom']) / 60, 2);
            $columnOptions = "[0.25,0.50,0.75]";
            $column = $this->createColumn("duration", $columnOptions, $description, $button, "float");
            $genericlearningactivity->column()->associate($column);
        }

        if (isset($data['resource'])) {
            switch ($data['resource']) {
                case 'persoon':
                    $genericlearningactivity->resourcePerson()->associate($data['personsource']);
                    $genericlearningactivity->resourceMaterial()->dissociate();
                    $columnOptions = "['persoon','internet','boek', 'alleen']";
                    $column = $this->createColumn("res_material_detail", $columnOptions, null, $button, "string");
                    $genericlearningactivity->column()->associate($column);
                    break;
                case 'internet':
                    $genericlearningactivity->resourceMaterial()->associate((new ResourceMaterial())->find(1));
                    $columnOptions = "['persoon','internet','boek', 'alleen']";
                    $column = $this->createColumn("res_material_detail", $columnOptions, $data['internetsource'], $button, "string");
                    $genericlearningactivity->column()->associate($column);
                    $genericlearningactivity->resourcePerson()->dissociate();
                    break;
                case 'boek':
                    $genericlearningactivity->resourceMaterial()->associate((new ResourceMaterial())->find(2));
                    $columnOptions = "['persoon','internet','boek', 'alleen']";
                    $column = $this->createColumn("res_material_detail", $columnOptions, $data['booksource'], $button, "string");
                    $genericlearningactivity->column()->associate($column);
                    $genericlearningactivity->resourcePerson()->dissociate();
                    break;
                case 'alleen':
                    $genericlearningactivity->resourcePerson()->dissociate();
                    $genericlearningactivity->resourceMaterial()->dissociate();
                    $columnOptions = "['persoon','internet','boek', 'alleen']";
                    $column = $this->createColumn("res_material_detail", $columnOptions, null, $button, "string");
                    $genericlearningactivity->column()->associate($column);
                    break;
            }
        }

        if (isset($data["category_id"])) {
            $genericlearningactivity->category()->associate((new Category())->find($data['category_id']));
        }

        if (isset($data["moeilijkheid"])) {
            $columnOptions = "['makkelijk','gemiddeld','moeilijk']";
            $column = $this->createColumn("difficulty", $columnOptions, $data['moeilijkheid'], $button, "string");
            $genericlearningactivity->column()->associate($column);
        }

        if (isset($data["status"])) {
            $columnOptions = "['afgerond','mee bezig','overgedragen']";
            $column = $this->createColumn("status", $columnOptions, $data['status'], $button, "string");
            $genericlearningactivity->column()->associate($column);
        }


        // Check if a reflection is attached and if so process it
        if (isset($data['reflection'])) {
            $this->activityReflectionFactory->create($data['reflection'], $genericLearningActivity);
            $this->genericLearningActivityRepository->save($genericLearningActivity);
        }

        return $genericLearningActivity;
    }

    private function getResourcePerson(array $data): ResourcePerson
    {
        if ($data['res_person'] === 'new') {
            return $this->resourcePersonFactory->createResourcePerson($data['new_rp']);
        }

        return (new ResourcePerson())->find($data['res_person']);
    }

    private function getResourceMaterial(array $data): ResourceMaterial
    {
        if ($data['res_material'] === 'new') {
            return $this->resourceMaterialFactory->createResourceMaterial($data['new_rm']);
        }

        return (new ResourceMaterial())->find($data['res_material']);
    }

    private function getTimeslot(array $data): Timeslot
    {
        if ($data['timeslot'] === 'new') {
            return $this->timeslotFactory->createTimeslot($data['new_timeslot']);
        }

        return (new Timeslot())->find($data['timeslot']);
    }

    private function getCategory($id): Category
    {
        if ($id === 'new') {
            return $this->categoryFactory->createCategory($this->data['newcat']);
        }

        return Category::find($id);
    }

}