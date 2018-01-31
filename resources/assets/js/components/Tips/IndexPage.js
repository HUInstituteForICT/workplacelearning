import {connect} from "react-redux";
import React from "react";
import {Link} from "react-router-dom";

const IndexPage = ({match, entities}) => <div>
    <h1>Tips</h1>

    <div style={{display: 'flex', flexDirection: 'row', flexWrap: 'wrap'}}>
        {Object.values(entities.tips).map(tip => <div key={tip.id} className="panel panel-default"
                                                      style={{minWidth: 230, margin: 20}}>
            <div className="panel-body">
                <strong>{Lang.get('react.tips.name')}:</strong> {tip.name}<br/>
                <strong>{Lang.get('react.tips.program')}:</strong> {
                entities.educationProgramTypes[
                    entities.statistics[
                        entities.coupledStatistics[
                            tip.coupled_statistics[0]
                            ].statistic_id
                        ].education_program_type
                    ].eptype_name
                || '-'}<br/>

                <strong>{Lang.get('react.tips.statistics')}:</strong> {tip.coupled_statistics.length}<br/>
                <br/><br/>

                <div style={{display: 'flex', flexDirection: 'row', justifyContent: 'space-between'}}>
                    <Link to={`/tip/${tip.id}`}>
                        <span className="btn btn-primary">{Lang.get('react.edit')}</span>
                    </Link>

                    <button className="btn btn-danger">{Lang.get('react.delete')}</button>
                </div>
            </div>
        </div>)}
    </div>
</div>;


const mapping = {
    state: state => ({entities: state.entities}),
    dispatch: dispatch => ({})
};

export default connect(mapping.state, mapping.dispatch)(IndexPage);