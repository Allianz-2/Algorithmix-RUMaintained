function toggleEmailEdit() {
    var emailField = document.getElementById('email');
    var saveButton = document.getElementById('save-button');
    var cancelButton = document.getElementById('cancel-button');
    var editButton = document.getElementById('edit-button');
    
    if (emailField.readOnly) {
        emailField.readOnly = false;
        cancelButton.hidden = false;
        saveButton.hidden = false;
        editButton.hidden = true;
    }
}

function toggleCancelEdit() {
    var emailField = document.getElementById('email');
    var saveButton = document.getElementById('save-button');
    var cancelButton = document.getElementById('cancel-button');
    var editButton = document.getElementById('edit-button');

    emailField.readOnly = true;
    editButton.hidden = false;
    cancelButton.hidden = true;
    saveButton.hidden = true;
}
function toggleResidenceEdit() {
    var residenceField = document.getElementById('residence');
    var saveResidenceButton = document.getElementById('res-save-button');
    var cancelResidenceButton = document.getElementById('res-cancel-button');
    var editResidenceButton = document.getElementById('res-edit-button');
    var hallField = document.getElementById('hallGroup');
    
    if (residenceField.disabled) {
        residenceField.disabled = false;
        cancelResidenceButton.hidden = false;
        saveResidenceButton.hidden = false;
        editResidenceButton.hidden = true;
        hallField.hidden = true;
    }
}

function toggleCancelResidenceEdit() {
    var residenceField = document.getElementById('residence');
    var saveResidenceButton = document.getElementById('res-save-button');
    var cancelResidenceButton = document.getElementById('res-cancel-button');
    var editResidenceButton = document.getElementById('res-edit-button');
    var hallField = document.getElementById('hallGroup');

    residenceField.disabled = true;
    editResidenceButton.hidden = false;
    cancelResidenceButton.hidden = true;
    saveResidenceButton.hidden = true;
    hallField.hidden = false;
}

function toggleSpecialisationEdit() {
    var specialisationField = document.getElementById('specialisation');
    var saveSpecialisationButton = document.getElementById('spec-save-button');
    var cancelSpecialisationButton = document.getElementById('spec-cancel-button');
    var editSpecialisationButton = document.getElementById('spec-edit-button');
    
    if (specialisationField.disabled) {
        specialisationField.disabled = false;
        cancelSpecialisationButton.hidden = false;
        saveSpecialisationButton.hidden = false;
        editSpecialisationButton.hidden = true;
    }
}

function toggleCancelSpecialisationEdit() {
    var specialisationField = document.getElementById('specialisation');
    var saveSpecialisationButton = document.getElementById('spec-save-button');
    var cancelSpecialisationButton = document.getElementById('spec-cancel-button');
    var editSpecialisationButton = document.getElementById('spec-edit-button');

    specialisationField.disabled = true;
    editSpecialisationButton.hidden = false;
    cancelSpecialisationButton.hidden = true;
    saveSpecialisationButton.hidden = true;
}


function toggleHallEdit() {
    var hallField = document.getElementById('hallChange');
    var saveHallButton = document.getElementById('hall-save-button');
    var cancelHallButton = document.getElementById('hall-cancel-button');
    var editHallButton = document.getElementById('hall-edit-button');
    
    if (hallField.disabled) {
        hallField.disabled = false;
        cancelHallButton.hidden = false;
        saveHallButton.hidden = false;
        editHallButton.hidden = true;
    }
}

function toggleCancelHallEdit() {
    var hallField = document.getElementById('hallChange');
    var saveHallButton = document.getElementById('hall-save-button');
    var cancelHallButton = document.getElementById('hall-cancel-button');
    var editHallButton = document.getElementById('hall-edit-button');

    hallField.disabled = true;
    editHallButton.hidden = false;
    cancelHallButton.hidden = true;
    saveHallButton.hidden = true;
}