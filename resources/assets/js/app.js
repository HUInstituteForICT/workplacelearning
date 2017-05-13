/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import React from "react";
import DOM from "react-dom";

import ActivityActingProcessTable from "./components/ActivityActingProcessTable/table";

DOM.render(<ActivityActingProcessTable />, document.getElementById('actingActivityProcessTable'));


// DOM.render(
// <HelloMessage name="John" />,
//     document.getElementById('wrapper')
// );