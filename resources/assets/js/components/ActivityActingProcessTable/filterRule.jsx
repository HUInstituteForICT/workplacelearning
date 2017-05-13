import React from "react"

export default class FilterRule extends React.Component {

    constructor(props) {
        super(props);

        // Pre-bind to make sure we have correct this context
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick() {
        this.props.onClickHandler(this.props.type, this.props.rule);
    }

    render() {
        let style = {display: "none"};

        return <label><input style={style} type="checkbox" value={this.props.rule} onChange={this.handleClick}/><span>{this.props.rule}</span></label>;
    }




}