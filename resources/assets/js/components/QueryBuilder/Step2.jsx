import * as React from "react";

export default class Step2 extends React.Component {
    constructor(props) {
        super(props);
    }
    _validate() {
        this.props.afterValid(this.state)
    }
    render() {
        if (this.props.currentStep !== 2) {
            return null;
        }

        return(
            <form>
                <button onClick={this._validate} />
            </form>
        );
    }
}