export default class ActingActivityProcessExporter {

    constructor(type, activities) {
        this.type = type;
        this.activities = activities;

        this.outputData = '';

        this[type]();
        this.download();
    }

    csv() {

        // Build headers and filter unwanted
        let headers = Object.keys(this.activities[0]);
        let unwantedColumns = ["id", "url"];
        unwantedColumns.forEach(column => {headers.splice(headers.indexOf(column), 1)});

        this.output(headers.join(",") + "\n");

        this.activities.forEach((activity, index) => {
            let values = headers.map(header => {
                if(unwantedColumns.indexOf(header) !== -1) return;
                return activity[header];
            });
            let dataString = values.join(",");
            this.output(index < this.activities.length ? dataString + "\n" : dataString);

        });
    }

    txt() {

    }

    output(str) {
        this.outputData += str;
    }

    download() {
        let a         = document.createElement('a');
        a.href        = 'data:attachment/csv,' +  encodeURIComponent(this.outputData);
        a.target      = '_blank';
        a.download    = 'myFile.csv';
        document.body.appendChild(a);
        a.click();
    }

}