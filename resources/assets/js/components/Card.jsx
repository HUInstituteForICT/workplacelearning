import * as React from "react";

export default class Card extends React.Component {

    constructor(props) {
        super(props);
    }

    componentDidMount() {

    }

    render() {
        return <div className="panel panel-default">
            <div className="panel-body">
                {this.props.children}
            </div>
        </div>;
    }


}