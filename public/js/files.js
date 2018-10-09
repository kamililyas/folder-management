
$( document ).ready(function () {

    fileTypes = fileTypesPassed;
    fileTypeFolder = fileTypeFolderPassed;
    maxFileSize = maxFileSizePassed;
    userFileKeyParentId = userFileKeyParentIdPassed;
    var currentFolderParentId = null;
    var lastFolderParentId = null;

    fetchUserFiles('');

    function fetchUserFiles(params) {
        jqxhr(
            {
                url: baseUrl + '/file' + params,
                async: true,
                type: 'GET',
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    // Object.keys(data).forEach(function (key) {
                    //     console.log(data[key]);
                    // });
                    if (data !== undefined && data.length != 0) {
                        if (data.user_files !== undefined && data.user_files.length != 0) {
                            appendItemsToFilesListTable(data.user_files);
                        }
                        else {
                            alert('No Data Found !');
                        }
                    }
                    else {
                        alert('No Data Found !');
                    }
                },
                error: function (data) {
                    alert('fail');
                    console.log(data);
                }
            },
            function () {},
            function () {},
            function () {}
        );
    }

    function appendItemsToFilesListTable(items) {

        for (var i = 0 ; i < items.length ; i++) {
            var folderTraversalTagOpen = '';
            var folderTraversalTagClose = '';
            var folderDataAttribute = '';
            if (items[i].file_type_id == getFileTypeFolderId()) {
                folderTraversalTagOpen = '<a href="#" class="' + folderTraversalLinkElement.split('.')[1] + '" ' + fileDataAttributeIdKey + '="' + items[i].id + '">';
                folderTraversalTagClose = '</a>';
                folderDataAttribute = fileDataAttributeFolderKey + '="' + fileDataAttributeFolderValue + '"';
            }

            $( filesListTableElement ).append(
                '<tr ' + fileDataAttributeIdKey + '="' + items[i].id + '" ' + folderDataAttribute + '>' +
                    '<td>' +
                        folderTraversalTagOpen +
                        '<img class="file-image" src="' + baseUrl + fileTypeImagePath + getFileTypeImageName(items[i].file_type_id) + fileTypeImageExtension + '" />' +
                        folderTraversalTagClose +
                        '<span class="' + inlineEditElements.split('.')[1] + '">' + items[i].name + '</span>' +
                    '</td>' +
                    '<td>' +
                        items[i].size +
                    '</td>' +
                    '<td class="dropdown">' +
                        items[i].updated_at +
                    '</td>' +
                '</tr>'
            );
        }

        bindFileActionContextMenuOnHover();
        bindFolderTraversalLinkClick();
        bindInlineEditOnClick();
    }

    function getFileTypeImageName(fileTypeId) {
        for (var i = 0 ; i < fileTypes.length ; i++) {
            if (fileTypes[i].id == fileTypeId) {
                return fileTypes[i].name.toLowerCase();
            }
        }
    }

    function getFileTypeFolderId() {
        for (var i = 0 ; i < fileTypes.length ; i++) {
            if (fileTypes[i].name == fileTypeFolder) {
                return fileTypes[i].id;
            }
        }
    }

    /* Bind File Action Context Menu On Hover of row */
    function bindFileActionContextMenuOnHover() {
        $( filesListTableElement + ' tr' ).not(':first').hover(function () {
            $( this ).children().last().append($(fileActionContextMenuElement)).addClass('open');
            $( this ).dropdown();
            $( fileActionContextMenuElement ).css('top', '50%');
            if (checkFileTypeFolderThroughDataElement($( this ))) {
                $( fileActionContextMenuDownloadElement ).hide();
            }
        }, function () {
            $( this ).children().last().removeClass('open');
            $( fileActionContextMenuDownloadElement ).show();
        });
    }

    /* Bind Folder traversal link click event */
    function bindFolderTraversalLinkClick() {
        $( folderTraversalLinkElement ).click(function (e) {
            e.preventDefault();
            var fileId = $( this ).data(fileDataAttributeIdKey.split(dataAttributeKeySeparator)[1]);
            clearFileListTable();
            lastFolderParentId = currentFolderParentId;
            fetchUserFiles('?' + userFileKeyParentId + '=' + fileId);
            currentFolderParentId = fileId;
            toggleLastFolderButton();
        });
    }

    function clearFileListTable() {
        $( filesListTableElement ).find('tr:gt(0)').remove();
    }

    function toggleLastFolderButton() {
        if (currentFolderParentId == null) {
            $( lastFolderButtonElement ).hide();
        }
        else{
            $( lastFolderButtonElement ).show();
        }
    }

    /* Bind Inline Edit event */
    function bindInlineEditOnClick() {
        var submitdata = {}
        /* this will make the save.php script take a long time so you can see the spinner ;) */
        submitdata['slow'] = true;
        submitdata['pwet'] = 'youpla';

        $( inlineEditElements ).editable(baseUrl + '/file',
            // function (value, settings) {
            //     console.log('asdadsasd');
            //     console.log(value);
            //     console.log(settings);
            //     return 'sdsdsdsds';
            // },
            {
            indicator : "<img src='img/spinner.svg' />",
            type : "text",
            // only limit to three letters example
            //pattern: "[A-Za-z]{3}",
            onedit : function() { console.log('If I return false edition will be canceled'); return true;},
            before : function() { console.log('Triggered before form appears')},
            callback : function(result, settings, submitdata) {
                console.log('Triggered after submit');
                console.log('Result: ' + result);
                console.log('Settings.width: ' + settings.width);
                console.log('Submitdata: ' + submitdata.pwet);
            },
            cancel : 'Cancel',
            cssclass : 'custom-class',
            cancelcssclass : 'btn btn-danger',
            submitcssclass : 'btn btn-success',
            maxlength : 200,
            // select all text
            select : true,
            label : 'This is a label',
            onreset : function() { console.log('Triggered before reset') },
            onsubmit : function() { console.log('Triggered before submit') },
            showfn : function(elem) { elem.fadeIn('slow') },
            submit : 'Save',
            submitdata : submitdata,
            /* submitdata as a function example
             submitdata : function(revert, settings, submitdata) {
             console.log("Revert text: " + revert);
             console.log(settings);
             console.log("User submitted text: " + submitdata.value);
             },
             */
            tooltip : "Click to edit...",
            width : 160
        });
    }

    function getContextMenuClosestDataElement(element) {
        return element.parents('tr');
    }

    function checkFileTypeFolderThroughDataElement(element) {
        if (element.data(fileDataAttributeFolderOnlyKey) == fileDataAttributeFolderValue) {
            return true;
        }
        return false;
    }

    /* Form openers and closers for both file creation and folder creation */
    $( fileUploadToggleElement ).click(function () {
        $( fileUploadFormElement ).toggle();
        $( fileUploadFileElement ).show().attr('required', 'required');
        $( folderCreateNameElement ).hide().removeAttr('required').val('');
    });
    $( folderCreateToggleElement ).click(function () {
        $( fileUploadFormElement ).toggle();
        $( folderCreateNameElement ).show().attr('required', 'required');
        $( fileUploadFileElement ).hide().removeAttr('required').val('');
    });

    /* File/Folder creation form submit event */
    $( fileUploadFormElement ).submit(function (e) {
        e.preventDefault();
        $( fileUploadFormElement ).append($( "<input>" ).attr("type", "hidden").attr("name", csrfTokenName).val($( csrfTokenElement ).val()));
        $( fileUploadFormElement ).append($( "<input>" ).attr("type", "hidden").attr("name", userFileKeyParentId).val(currentFolderParentId));
        var fd = new FormData($( fileUploadFormElement )[0]);
        /* FormData.append not working on different browsers */

        jqxhr(
            {
                url: baseUrl + '/file',
                type: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);

                    if (data !== undefined && data.length != 0) {
                        if (data.files !== undefined && data.files.length != 0) {
                            appendItemsToFilesListTable(data.files);
                        }
                        else {
                            alert('No Data Found !');
                        }
                    }
                    else {
                        alert('No Data Found !');
                    }
                },
                error: function (data) {
                    alert('fail');
                    console.log(data);
                }
            },
            function () {},
            function () {},
            function () {}
        );
    });

    /* Last Folder button click event */
    $( lastFolderButtonElement ).click(function () {
        clearFileListTable();
        fetchUserFiles('?' + userFileKeyParentId + '=' + lastFolderParentId);
        currentFolderParentId = lastFolderParentId;
        toggleLastFolderButton();
    });

    /* Download file button click event */
    $( fileActionContextMenuDownloadElement ).click(function () {
        window.location = baseUrl + '/file/' + getContextMenuClosestDataElement($( this )).data(fileDataAttributeIdOnlyKey);
    });
});