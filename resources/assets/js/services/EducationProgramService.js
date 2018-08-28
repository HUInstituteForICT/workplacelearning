const base = '/education-programs/api/';

export default class EducationProgramService {


    static getEducationPrograms(callback) {
        axios.get(base + 'education-programs')
            .then(callback)
            .catch(error => {
                console.log("EducationPrograms service error: " + error);
            });
    }

    static getEditableEducationProgram(callback, id) {
        axios.get(base + 'editable-education-program/' + id)
            .then(callback)
            .catch(error => {
                console.log("EducationPrograms edit service error: " + error);
            })
    }

    static createEducationProgram(data, callback) {
        axios.post(base + 'education-program', data)
            .then(callback)
            .catch(error => {console.log("Unable to create education program: " + error)});
    }

    static deleteEducationProgram(id, callback) {
        axios.delete(base + 'education-program/' + id).then(callback).catch(error => {
            console.error(error);
            if (error.response.data.status === "error" && error.response.data.hasOwnProperty("message")) {
                alert(error.response.data.message);
            }
        })
    }

    static createEntity(programId, type, value, callback) {
        axios.post(base + 'education-program/' + programId + '/entity', {
            type: type,
            value: value
        })
            .then(callback)
            .catch(error => {
                console.log("EducationPrograms create entity service error: " + error);
            });
    }

    static deleteEntity(type, id, callback) {
        axios.post(base + 'cohort/entity/' + id + '/delete', {type: type})
            .then(callback)
            .catch(error => {
                if (error.response.data.status === "error" && error.response.data.hasOwnProperty("message")) {
                    alert(error.response.data.message);
                }
            })
    }

    static updateEntity(id, data, callback) {
        axios.put(base + 'education-program/entity/' + id , data).then(callback);
    }

    static updateName(id, data, callback) {
        axios.put(base + 'education-program/' + id, data)
            .then(callback)
            .catch(error => {
                console.log("EducationPrograms update service error: " + error);
            })
    }

    static uploadCompetenceDescription(cohortId, fileData, callback, onError) {
        axios.post(base + 'education-program/cohort/' + cohortId + '/competence-description', {file: fileData})
            .then(callback)
            .catch(error => {
                console.log("Unable to upload competence description: " + error);
                onError(error);
            });
    }

    static removeCompetenceDescription(cohortId, callback) {
        axios.get(base + 'education-program/cohort/' + cohortId + '/competence-description/remove')
            .then(callback)
            .catch(error=> {
                console.log("Unable to upload competence description: " + error);
            });
    }

    static toggleDisable(id, callback) {
        axios.get(base + 'education-program/' + id + '/disable')
            .then(callback)
            .catch(error => {console.log("Unable to toggle disabled state of program: " + error)});
    }

    static createCohort(programId, callback) {
        axios.post(base + 'education-program/' + programId + '/cohort/create').then(callback);
    }

    static updateCohort(id, cohort, callback) {
        axios.put(base + 'education-program/cohort/' + id + '/update', cohort).then(callback);
    }

    static loadCohort(id, callback) {
        axios.get(base + 'education-program/cohort/' + id).then(callback);
    }

    static deleteCohort(id, callback) {
        axios.delete(base + 'education-program/cohort/' + id).then(callback).catch(error => {
            console.error(error);
            if (error.response.data.status === "error" && error.response.data.hasOwnProperty("message")) {
                alert(error.response.data.message);
            }
        })
    }

    static toggleDisableCohort(id, callback) {
        axios.get(base + 'education-program/cohort/' + id + '/disable')
            .then(callback)
            .catch(error => {console.log("Unable to toggle disabled state of cohort: " + error)});
    }

    static cloneCohort(id, callback) {
        axios.get(base + 'education-program/cohort/' + id + '/clone').then(callback);
    }
}