import React from "react";

export default class Row extends React.Component {

    constructor(props) {
        super(props);
        this.state = {

            visible: false

        }
    }

    render() {
        let activity = this.props.activity;
        let className = "fa fa-arrow-circle-o-" + (this.state.visible ? 'up' : 'down');

        const actionStyle = {
            paddingLeft: 10, paddingRight: 10,
            marginLeft: 10, marginRight: 10
        };

        return <tr className="activityExport">
            <td>
                <a style={actionStyle} href={activity.url}><i className="glyphicon glyphicon-pencil" aria-hidden="true"/></a>
                <a style={actionStyle} onClick={() => confirm(Lang.get("react.delete-confirm")) ? window.location.href = "/producing/process/delete/" + activity.id: null}><i className={"glyphicon glyphicon-trash"} aria-hidden={"true"}/></a>
            </td>
            <td>{activity.duration}</td>

            {(activity.description.length > 30) &&
            <td>

                <i onClick={() => this.setState({visible: !this.state.visible})}
                   className={className}/>


            </td>
            }
            <td colSpan={activity.description.length <= 30 ? 2 : 1}>
                {activity.description.length > 30 &&
                <span>
                        {this.state.visible && <span>{activity.description}</span>}
                    {!this.state.visible && <span>{activity.description.substr(0, 30)}...</span>}
                    </span>
                }
                {activity.description.length <= 30 &&
                activity.description
                }
            </td>
            <td style={{wordBreak: 'break-all', width: '15%'}}>{activity.resourceDetail}</td>
            <td>{activity.category}</td>
            <td>{activity.difficulty}</td>
            <td>{activity.status}</td>
            <td>{activity.chain}</td>
            <td>
                {activity.feedback !== null && <a href={`/producing/feedback/${activity.feedback}`}>Feedback</a>}
            </td>

        </tr>
    }

}