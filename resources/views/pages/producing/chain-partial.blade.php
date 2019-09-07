<a id="chainModalOpen">
    {{ __('process.chain.manage') }}
</a>


<div class="modal fade" id="chainModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
                <h4 class="modal-title">{{ __('process.chain.chain-activity') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 ">
                        <label for="chainName">{{ __('process.chain.name') }}</label>
                        <input type="text" class="form-control" id="chainName"/>
                        <br/>
                        <a type="button" class="btn btn-primary"
                           id="createChainButton">{{ __('process.chain.create') }}</a>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-responsive table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('process.chain.chains') }}</th>
                                <th/>
                            </tr>
                            </thead>
                            <tbody id="chainTableBody">
                            @foreach($chains as $chain)
                                <tr id="chain-row-{{$chain->id}}">
                                    <td>{{ $chain->name }} {{ '(' . $chain->hours()  . ' ' . strtolower(__('activity.hours')) . ')' }}</td>
                                    <td
                                            data-id="{{$chain->id}}"
                                            data-name="{{ $chain->name }}"
                                            style="text-align: right; width:50%;"
                                    >

                                        @if($chain->status === 1)
                                            <button class="chainReopenButton btn btn-default"
                                                    type="button">
                                                {{ __('process.chain.reopen') }}
                                            </button>
                                        @else
                                            <button class="chainFinishButton btn btn-success"
                                                    type="button">
                                                {{ __('process.chain.finish') }}
                                            </button>
                                        @endif
                                        <button id="chainUpdate-{{$chain->id}}" type="button"
                                                class="btn btn-primary chainUpdateModalOpen">
                                            {{ __('process.chain.rename') }}
                                        </button>
                                        <button class="btn btn-danger chainDeleteModalButton" type="button">
                                            {{ __('process.chain.delete') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ __('general.close') }}</button>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chainUpdateModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
                <h4 class="modal-title">{{ __('process.chain.chain-activity') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label for="chainUpdateName">{{ __('process.chain.name') }}</label>
                        <input type="text" class="form-control" id="chainUpdateName" maxlength="255"/>
                        <br/>
                        <a type="button" class="btn btn-primary"
                           id="chainUpdateSaveButton">{{ __('process.chain.save') }}</a>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ __('general.close') }}</button>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="chainDeleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
                <h4 class="modal-title">{{ __('process.chain.delete') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p>{{ __('process.chain.delete-confirm') }}</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="chainDeleteButton">
                    {{ __('process.chain.delete') }}
                </button>
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ __('process.chain.cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        function openChainModal() {
            $('#chainModal').modal('show');
        }

        function closeChainModal() {
            $('#chainModal').modal('hide');
        }

        function openChainUpdateModal() {
            $('#chainUpdateModal').modal('show');
        }

        function closeChainUpdateModal() {
            $('#chainUpdateModal').modal('hide');
        }

        function openChainDeleteModal() {
            $('#chainDeleteModal').modal('show');
        }

        function closeChainDeleteModal() {
            $('#chainDeleteModal').modal('hide');
        }

        function reloadAndReopen() {
            location.replace(location.pathname + '#chains');
            location.reload();
        }

        if (location.hash === '#chains') {
            openChainModal();
            history.replaceState({}, document.title, location.pathname);
        }

        $('#chainModalOpen').click(openChainModal);
        $('#chainUpdateModal').on('hide.bs.modal', openChainModal);
        $('#chainDeleteModal').on('hide.bs.modal', openChainModal);

        $('.chainDeleteModalButton').click(function () {
            $('#chainModal').modal('hide');
            $('#chainDeleteModal').modal('show');
        });

        $('body').on('click', '.chainUpdateModalOpen', function () {
            closeChainModal();
            openChainUpdateModal();

            $('#chainUpdateName').val($(this).parent().data('name'));
            $('#chainUpdateSaveButton').data('id', $(this).parent().data('id'));
        });

        $('body').on('click', '.chainDeleteModalButton', function () {
            closeChainModal();
            openChainDeleteModal();

            $('#chainDeleteButton').data('id', $(this).parent().data('id'));
        });


        const chainSaveUrl = '{{ route('chain-save', ['chain' => ':id']) }}';

        // Save finish
        $('body').on('click', '.chainFinishButton', function () {
            const id = $(this).parent().data('id');

            updateStatus(id, 1).then(function () {
                reloadAndReopen();
            });
        });

        function updateStatus(id, status) {
            return saveChain(id, {status})
        }

        $('body').on('click', '.chainReopenButton', function () {
            const id = $(this).parent().data('id');
            updateStatus(id, 0).then(function () {
                reloadAndReopen();
            });
        });



        // Save rename
        $('body').on('click', '#chainUpdateSaveButton', function () {
            const id = $(this).data('id');
            const data = {name: $('#chainUpdateName').val()};

            saveChain(id, data).then(function () {
                $("#chainSelect option#chain-select-" + id).text(data.name);
                $("#chain-row-" + id + " td:first-child").text(data.name);

                reloadAndReopen();
            });
        });

        // Delete chain
        $('body').on('click', '#chainDeleteButton', function () {
            const id = $(this).data('id');
            window.location.href = '{{ route('chain-delete', ['chain' => ':id']) }}'.replace(':id', id);
        });

        function saveChain(id, data) {
            return $.ajax({
                type: 'PUT',
                url: chainSaveUrl.replace(':id', id),
                data
            })
        }

        $('#createChainButton').click(function () {
            const name = $('#chainName').val();
            $.post('{{ route('chain-create') }}', {name: name}).then(function (chain) {
                const newChainOption = document.createElement('option');
                newChainOption.value = chain.id;
                newChainOption.text = chain.name;
                newChainOption.selected = true;
                newChainOption.id = 'chain-select-' + chain.id;
                document.getElementById('chainSelect').add(newChainOption);

                createNewChainRow(chain);
                $('#chainName').val('');
                $('#chainModal').modal('hide');
            })
        });

        function createNewChainRow(chain) {
            const row = $('<tr>');
            row.prop('id', 'chain-row-' + chain.id);

            const nameCell = $('<td>');
            nameCell.text(chain.name);

            const actionsCell = $('<td>');
            actionsCell.data('id', chain.id);
            actionsCell.data('name', chain.name);

            const finishButton = $('<button class="chainFinishButton btn btn-success" type="button">');
            finishButton.text("{{ __('process.chain.finish') }}");

            const renameButton = $('<button class="btn btn-primary chainUpdateModalOpen" type="button">');
            renameButton.prop('id', "chainUpdate-" + chain.id);
            renameButton.text("{{ __('process.chain.rename') }}");

            const deleteButton = $('<button class="btn btn-danger chainDeleteModalButton" type="button" style="float:right;">');
            deleteButton.text("{{ __('process.chain.delete') }}");

            actionsCell.append(finishButton);
            actionsCell.append('&nbsp;');
            actionsCell.append(renameButton);
            actionsCell.append('&nbsp;');
            actionsCell.append(deleteButton);

            row.append(nameCell);
            row.append(actionsCell);

            $('#chainTableBody').append(row);
        }

    });
</script>