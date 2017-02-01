<?php
/**
 * This file (ChartController.php) was created on 05/29/2016 at 18:20.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */

namespace App\Http\Controllers;

use App\Chart;
use CpChart\Factory\Factory;

class ChartController extends Controller
{

    //////////////////////
    ////// SETTINGS //////
    //////////////////////
    // Background
    private $backgroundSettings = array(
        "R" => 226,
        "G" => 226,
        "B" => 226,
        "Dash" => 0,
        "DashR" => 193,
        "DashG" => 172,
        "DashB" => 237
    );
    //Draw a gradient overlay
    private $gradientSettings = array(
        "StartR" => 0,
        "StartG" => 0,
        "StartB" => 0,
        "EndR" => 255,
        "EndG" => 255,
        "EndB" => 255,
        "Alpha" => 0
    );
    // The Chart Title Settings
    private $chartTitleSettings = array("R" => 255, "G" => 255, "B" => 255, "Align" => TEXT_ALIGN_TOPLEFT);

    /* DND FROM HERE ON */
    // Chart Factory
    protected $factory;
    // The Chart Object
    protected $chart;
    // The Image Object
    protected $chartImage;
    // Data array
    protected $data = array("No Data" => 1);
    protected $title = "";

    public function __construct()
    {
        try {
            $this->factory = new Factory();
        } catch (Exception $e) {
            echo sprintf('There was an error: %s', $e->getMessage());
        }
    }

    public function show($id){
        // Attempt to get the chart data, auto return if no result or no access
        $chart = Chart::where('id', '=', $id)->firstOrFail();

        // Now that we have the model, generate a chart.
        $this->setDatapoints($chart->title, $chart->getDataset());
        return $this->draw();
    }

    public function setDatapoints($description, $datapoints = array())
    {
        $this->title = $description;
        // First, split the keys from data.
        $labels = array_keys($datapoints);
        $data = array_values($datapoints);

        // Next, add the data to the factory
        $this->data = $this->factory->newData($data, $description);
        $this->data->setSerieDescription($description, "Labels");

        // Set the absissa serie labels
        $this->data->addPoints($labels, "Labels");
        $this->data->setAbscissa("Labels");

        // Create the image file (Width, height, $data)
        $this->chartImage = $this->factory->newImage(320, 200, $this->data);
    }

    public function draw(){
        $this->chartImage->drawFilledRectangle(0, 0, 320, 200, $this->backgroundSettings);

        $this->chartImage->drawGradientArea(0, 0, 320, 200, DIRECTION_VERTICAL, $this->gradientSettings);
        $this->chartImage->drawGradientArea(
            0,
            0,
            320,
            20,
            DIRECTION_VERTICAL,
            array(
                "StartR" => 0,
                "StartG" => 161,
                "StartB" => 226,
                "EndR" => 0,
                "EndG" => 161,
                "EndB" => 226,
                "Alpha" => 100
            )
        );

        // Draw the picture title
        $this->chartImage->setFontProperties(array("FontName" => "calibri.ttf", "FontSize" => 12));
        $this->chartImage->drawText(3, 3, $this->title, $this->chartTitleSettings);

        // Set the default font properties (for legend/chart text)
        $this->chartImage->setFontProperties(array("FontName" => "calibri.ttf", "FontSize" => 10, "R" => 80, "G" => 80, "B" => 80));

        try {
            // Draw the Chart
            $this->chart = $this->factory->newChart("pie", $this->chartImage, $this->data);
            $this->chart->draw2DPie(100, 100, array("Border" => true, "WriteValues"=>PIE_VALUE_NATURAL,"ValueSuffix"=> " uur","ValueR"=>0,"ValueG"=>0,"ValueB"=>0,));
            // Draw the Pie Legend
            $this->chart->drawPieLegend(210,35,array("Style"=>LEGEND_BOX,"Mode"=>LEGEND_VERTICAL));

            // Output to browser
            $this->chart->pChartObject->stroke();

        } catch(Exception $e){
            echo sprintf('There was an error: %s', $e->getMessage());
        }
    }
}