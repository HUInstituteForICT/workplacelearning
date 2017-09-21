

import "./bootstrap";

import React from "react";
import DOM from "react-dom";

// Import components
import ActivityActingProcessTable from "./components/ActivityActingProcessTable/table";
import EducationProgramsApp from "./components/EducationProgramsApp/educationProgramsApp";
import ActivityProducingProcessTable from "./components/ActivityProducingProcessTable/table";



const Apps = {
    ActivityActingProcessTable,
    ActivityProducingProcessTable,
    EducationProgramsApp
};

// Automatically mount if one of the above declared Apps exist in the DOM
document.querySelectorAll('.__reactRoot').forEach((element) => {
    let App = Apps[element.id];
    if(!App) return;

    let props = Object.assign({}, element.dataset);

    DOM.render(<App {...props} />, element);
});