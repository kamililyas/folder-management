<button id="file-upload" class="btn"><i class="fa fa-file"></i> Upload File</button>
<form class="form-border" id="file-upload-form" enctype="multipart/form-data" method="post" style="display: none;">
    <input id="form-file-element" type="file" name="file" required />
    <input id="form-name-element" type="text" name="name" style="display: none;" required>
    <button id="file-upload-submit" type="submit" class="btn">upload</button>
</form>
<input id="csrf-token-field" type="hidden" name="_token" value="{{ csrf_token() }}">
<button id="folder-create" class="btn"><i class="fa fa-folder"></i> New Folder</button>
<br />
<button id="last-folder" class="btn" style="display: none;"><i class="fa fa-arrow-circle-up"></i> Up</button>
<table class="table table-hover" id="files-list-table">
    <tbody>
        <tr>
            <th><strong>{{ trans('headings.folders_files') }}</strong></th>
            <th><strong>{{ trans('headings.folders_files_size') }}</strong></th>
            <th><strong>{{ trans('headings.folders_files_modified') }}</strong></th>
        </tr>
    </tbody>
</table>
<ul id="file-action-context-menu" class="dropdown-menu" role="menu">
    <li><a tabindex="-1" href="#" class="download-file">Download</a></li>
    <li><a tabindex="-1" href="#" class="rename-file">Rename</a></li>
    <li><a tabindex="-1" href="#" class="delete-file">Delete</a></li>
</ul>
<script>
    {!! 'var fileTypesPassed = ' . $fileTypes !!};
    {!! 'var fileTypeFolderPassed = "' . config('constants.FILE_TYPE_FOLDER') . '"' !!};
    {!! 'var maxFileSizePassed = "' . config('constants.MAX_FILE_SIZE') . '"' !!};
    {!! 'var userFileKeyParentIdPassed = "' . config('constants.USER_FILE_KEY_PARENT_ID') . '"' !!};
</script>