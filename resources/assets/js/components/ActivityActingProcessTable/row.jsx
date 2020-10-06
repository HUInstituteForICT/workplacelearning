import React, {Fragment} from "react";

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
            <td width="15%">
                {(activity.isSaved < 1) &&
                    <a style={actionStyle} onClick={() => confirm(Lang.get("Weet u zeker dat u dit wilt opslaan?")) ? window.location.href = "/acting/process/save/" + activity.id: null}>
                        <img className="save_activity_icon" src="../assets/img/bookmark-blauw.svg"/>
                    </a>
                ||
                    <span style={actionStyle}>
                        <img className="save_activity_icogit check on" src="../assets/img/bookmark-blauw-ingevuld.svg"/>
                    </span>
                }
                <a style={actionStyle} href={activity.url}><i className="glyphicon glyphicon-pencil"
                                                              aria-hidden="true"/></a>
                <a style={actionStyle}
                   onClick={() => confirm(Lang.get("react.delete-confirm")) ? window.location.href = "/acting/process/delete/" + activity.id : null}><i
                    className={"glyphicon glyphicon-trash"} aria-hidden={"true"}/></a>
            </td>
            <td>
                {(activity.situation.length > 30 || (activity.lessonsLearned !== null && activity.lessonsLearned.length > 30)) &&
                <i onClick={() => this.setState({visible: !this.state.visible})}
                   className={className}/>
                }

            </td>
            <td>{activity.date}</td>
            <td>
                {activity.situation.length > 30 &&
                <span>
                        {this.state.visible && <span>{activity.situation.split('\n').map((item, key) => {
                            return <Fragment key={key}>{item}<br/></Fragment>
                        })}</span>}
                    {!this.state.visible && <span>{activity.situation.substr(0, 30)}...</span>}
                    </span>
                }
                {activity.situation.length <= 30 &&
                activity.situation.split('\n').map((item, key) => {
                    return <Fragment key={key}>{item}<br/></Fragment>
                })
                }
            </td>
            <td>{activity.timeslot}</td>
            <td>{activity.resourcePerson}</td>
            <td>{activity.resourceMaterial}</td>
            <td>

                {activity.lessonsLearned !== null && <div>

                    {activity.lessonsLearned.length > 30 &&
                    <span>
                        {this.state.visible && <span>{activity.lessonsLearned}</span>}
                        {!this.state.visible && <span>{activity.lessonsLearned.substr(0, 30)}...</span>}
                    </span>
                    }
                    {activity.lessonsLearned.length <= 30 &&
                    activity.lessonsLearned
                    }
                </div> || '-'}

            </td>
            <td>{activity.learningGoal}</td>
            <td>{activity.competence.join(', ')}</td>
            <td>
                {
                    activity.evidence.length > 0 && <ul>
                        {activity.evidence.map(evidence => <li key={evidence.url}><a
                            href={evidence.url}>{evidence.name}</a></li>)}
                    </ul>
                }
            </td>
            <td>
                {activity.reflection && activity.reflection.url && <a href={activity.reflection.url}>Download</a>}
            </td>

        </tr>
    }

}
