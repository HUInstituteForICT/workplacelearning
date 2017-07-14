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
        axios.post(base + 'education-program/entity/'+id+'/delete', {type: type })
            .then(callback)
            .catch(error => {
                console.log("EducationPrograms delete entity service error: " + error);
            })
    }
}