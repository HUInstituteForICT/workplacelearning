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

        //Get all WPLP of the student
        const wplpsOfStudent = workplacelearningperiods.filter(wplp => wplp.student_id === id);
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


</script>