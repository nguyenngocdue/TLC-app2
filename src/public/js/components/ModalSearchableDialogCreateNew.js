function prepareForUpdate(hitId) {
    // console.log(hitId)
    const hit = modalSearchableDialogHits[hitId]
    $('#txtId').val(hit.id)
    $('#txtRegNo').val(hit.reg_no)
    $('#txtName').val(hit.name)
    $('#txtAddress').val(hit.address)
    modalSearchableDialogMode = 'update'
    validateForm()
}
function cancelUpdate() {
    $('#txtRegNo, #txtName, #txtAddress').val('')
    modalSearchableDialogMode = 'createNew'
    validateForm()
}
function validateForm() {
    const regNo = $('#txtRegNo').val()
    const name = $('#txtName').val()
    const address = $('#txtAddress').val()

    const isValidated = !regNo || !name || !address
    $('#btnCreateNew').prop('disabled', isValidated)
    $('#btnUpdate').prop('disabled', isValidated)

    switch (modalSearchableDialogMode) {
        case 'createNew':
            $('#btnUpdate').hide()
            $('#btnCancel').hide()
            $('#btnCreateNew').show()
            $('#divCardTitle').text('Create New')
            break
        case 'update':
            $('#btnUpdate').show()
            $('#btnCancel').show()
            $('#btnCreateNew').hide()
            $('#divCardTitle').text('Update')
            break
    }
}

const updateCachedK = (tableName, id, value) => {
    // console.log('updateCachedK', tableName, id, value)
    const dataset = k[tableName]
    const index = dataset.findIndex((item) => item.id == id)
    if (index > -1) {
        // console.log('updateCachedK', 'found', index)
        dataset[index] = value
    } else {
        // console.log('updateCachedK', 'push')
        dataset.push(value)
    }
}

const onDocumentReady = (modalId, isMultiple, urlCreateNew, urlUpdate, owner_id, tableName) => {
    $('#txtRegNo, #txtName, #txtAddress').on('input', function () {
        validateForm()
    })
    const sendAjax = (type) => {
        if (type == 'createNew') {
            const data = {
                reg_no: $('#txtRegNo').val(),
                name: $('#txtName').val(),
                address: $('#txtAddress').val(),
                owner_id,
            }
            $.ajax({
                method: 'POST',
                url: urlCreateNew,
                data,
                success: function (response) {
                    if (response.success) {
                        // console.log(response)
                        const hit = response.hits
                        const insertedId = hit.id
                        modalSearchableDialogHits[insertedId] = hit

                        updateCachedK(tableName, insertedId, { ...data, id: insertedId })

                        $('#txtRegNo, #txtName, #txtAddress').val('')
                        validateForm()

                        const selectValueCtrl = getEById(modalId + '_selectedValue')
                        const selectValueCtrlVal = selectValueCtrl.val()

                        let newIdStrs
                        if (isMultiple) selectValueCtrlVal == '' ? insertedId : selectValueCtrlVal + ',' + insertedId
                        else newIdStrs = insertedId

                        selectValueCtrl.val(newIdStrs)
                        //Reset keyword and reload the table lines
                        getEById(modalId + '_txtName')
                            .val('')
                            .trigger('keyup')
                        toastr.success('Vendor created successfully')
                    }
                },
                error: function (e) {
                    toastr.error(e.responseJSON.message)
                },
            })
        }
        if (type == 'update') {
            const id = $('#txtId').val()
            const data = {
                reg_no: $('#txtRegNo').val(),
                name: $('#txtName').val(),
                address: $('#txtAddress').val(),
                id,
            }
            $.ajax({
                method: 'POST',
                url: urlUpdate,
                data: {
                    lines: [
                        { id, fieldName: 'reg_no', value: $('#txtRegNo').val() },
                        { id, fieldName: 'name', value: $('#txtName').val() },
                        { id, fieldName: 'address', value: $('#txtAddress').val() },
                    ],
                },
                success: function (response) {
                    if (response.success) {
                        // console.log(response.hits)
                        updateCachedK(tableName, id, data)

                        $('#txtRegNo, #txtName, #txtAddress').val('')
                        modalSearchableDialogMode = 'createNew'
                        validateForm()

                        getEById(modalId + '_txtName')
                            .val('')
                            .trigger('keyup')
                        toastr.success('Vendor updated successfully')
                        // console.log(modalSearchableDialogHits)

                        // modalSearchableDialogOnSelectHandleText(modalId)
                    }
                },
                error: function (e) {
                    toastr.error(e.responseJSON.message)
                },
            })
        }
    }
    $('#btnCreateNew').on('click', () => sendAjax('createNew'))
    $('#btnUpdate').on('click', () => sendAjax('update'))
    validateForm()
}
