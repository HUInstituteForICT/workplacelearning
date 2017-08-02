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

    static uploadCompetenceDescription(programId, fileData, callback) {
        axios.post(base + 'education-program/' + programId + '/competence-description', {file: fileData})
            .then(callback)
            .catch(error => {
                console.log("Unable to upload competence description: " + error)
            });
    }
}