import * as React from "react";
import Step1 from "./Step1";
import Step2 from "./Step2";
import Step3 from "./Step3";
import Step4 from "./Step4";

export default class QueryBuilder extends React.Component {

    constructor() {
        super();
        this.state = {
            currentStep: 1
        };

        this._next = this._next.bind(this);
        this._prev = this._prev.bind(this);
    }

    _next() {
        let currentStep = this.state.currentStep;

        if (currentStep >= 4) {
            currentStep = 4;
        } else {
            currentStep = currentStep + 1;
        }

        this.setState({
            currentStep: currentStep
        });
    }

    _prev() {
        let currentStep = this.state.currentStep;
        if (currentStep <= 1) {
            currentStep = 1;
        } else {
            currentStep = currentStep - 1;
        }

        this.setState({
            currentStep: currentStep
        });
    }

    render() {

        let currentStep = this.state.currentStep;
        return(
            <div>
                <Step1 currentStep={currentStep} />
                <Step2 currentStep={currentStep} />
                <Step3 currentStep={currentStep} />
                <Step4 currentStep={currentStep} />
                <button onClick={this._next}>Next</button>
                <button onClick={this._prev}>Prev</button>
            </div>
        );
    }
}