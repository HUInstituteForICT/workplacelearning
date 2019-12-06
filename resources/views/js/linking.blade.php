<script>
    document.getElementById('teacher_id').style.display = "none";
    document.getElementById('wplp').style.display = "none";
    document.getElementById('step-3').style.display = "none";
    document.getElementById('error').style.display = "none";
    document.getElementById('selectWPLP').onchange = chooseWPLP;
    const coupleButton = document.getElementById('coupleButton');

    function showModal() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function filterFunction() {
        const input = document.getElementById("dropdownInput");
        const filter = input.value.toUpperCase();

        Array.from(document.getElementById("myDropdown")
            .getElementsByTagName("a"))
            .forEach(listEntry => {
                const txtValue = listEntry.textContent || listEntry.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    listEntry.style.display = '';
                } else {
                    listEntry.style.display = 'none';
                }
            });
    }

    function chooseDocent(id) {
        document.getElementById('teacher_id').value = id;

        const teacher = teachers.find(teacher => teacher.student_id === id);
        if (!teacher) {
            return;
        }
        document.getElementById('chosenTeacher').innerHTML = teacher.firstname + " " + teacher.lastname + '<br>' + teacher.email;

        // get all students that are linked to the selected teacher.
        var linkedStudents = [];
        workplacelearningperiods.forEach(wplp => {
            if (wplp.teacher_id === id) {
                const student = students.find(student => student.student_id === wplp.student_id);
                linkedStudents.push(student);
            }
        });

        // remove Duplicates from the student list.
        let cachedObject = {};
        linkedStudents.map((student) => cachedObject[student.student_id] = student);
        uniqueLinkedStudents = Object.values(cachedObject);

        // display the list of all students, show a message if there is no student.
        listGroup = document.getElementById('linked-students');
        listGroup.innerHTML = '';
        if (uniqueLinkedStudents.length == 0) {
            msg = document.createElement('p');
            msg.innerText = `${Lang.get('linking.geen-student')}`;
            listGroup.appendChild(msg);
        } else {
            uniqueLinkedStudents.forEach(student => {
                item = document.createElement('a');
                item.setAttribute('class', 'list-group-item');
                item.innerText = `${ student.studentnr } - ${ student.firstname } ${ student.lastname }`;
                listGroup.appendChild(item);
            });
        }

        Array.from(document.getElementById("myDropdown").getElementsByTagName("a"))
            .forEach(listEntry => listEntry.style.display = 'none');
    }

    function chooseWPLP() {
        dropdown = document.getElementById('selectWPLP')
        document.getElementById('wplp').value = dropdown.options[dropdown.selectedIndex].value;
    }

    function chooseStudent(id) {
        document.getElementById("myDropdown").classList.toggle("show");
        const select = document.getElementById("selectWPLP");
        removeOptions(select);
        document.getElementById('error').style.display = "none";
        document.getElementById('step-3').style.display = "none";

        const student = students.find(student => student.student_id === id);
        if (!student) {
            return;
        }
        const chosenStudentText = student.studentnr + ' - ' + student.firstname + " " + student.lastname;

        const defaultOption = document.createElement("option");
        defaultOption.innerText = 'Kies een stage';
        defaultOption.setAttribute('disabled', 'disabled');
        defaultOption.setAttribute('selected', 'selected');
        select.appendChild(defaultOption);

        document.getElementById('step-3').style.display = "";
        document.getElementById('error').style.display = "none";

        // Get all WPLPs of the student (only those who are not yet linked with a teacher.)
        const wplpsOfStudent = workplacelearningperiods.filter(wplp => wplp.student_id === id && wplp.teacher_id === null);
        wplpsOfStudent.forEach(wplp => {
            const workplaceOption = document.createElement("option");
            workplaceOption.textContent = `${wplp.workplace.wp_name} (${new Date(wplp.startdate).toLocaleDateString("en-GB")} ${Lang.get('general.until-header')} ${new Date(wplp.enddate).toLocaleDateString("en-GB")})`;
            workplaceOption.value = wplp.wplp_id;
            select.appendChild(workplaceOption);
        });

        //Error if student has no WPLP
        if (select.options.length === 1) {
            document.getElementById('error').style.display = "";
            document.getElementById('step-3').style.display = "none";
            coupleButton.setAttribute('disabled', 'disabled')
        } else {
            coupleButton.removeAttribute('disabled')
        }


        //Clear dropdown
        document.getElementById('dropdownInput').value = chosenStudentText;
        Array.from(document.getElementById("myDropdown").getElementsByTagName("a"))
            .forEach(listEntry => listEntry.style.display = 'none');
    }

    function removeOptions(selectbox) {
        var i;
        for (i = selectbox.options.length - 1; i >= 0; i--) {
            selectbox.remove(i);
        }

    }

    function getCSTableData(){
       var TableData;
       TableData = storeTblValues()
       TableData = JSON.stringify(TableData);

       $.ajax({
           type: 'POST',
           url: "/admin/linking/update-workplacelearningperiod-csv-save",
           data: {'tableData' : TableData},
           success:function(data) {
               alert("De suggestie is opgeslagen.");
                window.location.href = "/admin/linking";
               }
       })
    }

    function storeTblValues() {
        var TableData = new Array();
        $('#csvSuggestion tr').each(function(row,tr){
            TableData[row] = {
                "TeacherName" :  $(tr).find('td:eq(0)').text(),
                "TeacherEmail" : $(tr).find('td:eq(1)').text(),
                "StudentName" :  $(tr).find('td:eq(2)').text(),
                "StudentEmail" :  $(tr).find('td:eq(3)').text()
            }
        });
        TableData.shift();
        return TableData;

    }

    function downloadNotKnownStudents(notKnownStudents) {
        var csv = 'Studenten die nog niet gekoppeld zijn:\n';
        notKnownStudents.forEach(function(row) {
            if (typeof row == 'string') {
                csv += row;
            } else {
                csv += row.join(',');
            }

            csv += "\n";
        });
        var today = new Date();
        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';
        hiddenElement.download = today.getDate() + '-'+ today.getMonth() + '-' +today.getFullYear() + '-'+ today.getHours() + ':' + today.getMinutes() +  '-'+ 'Niet-gekoppelde-studenten.csv' ;
        hiddenElement.click();

    }



</script>
