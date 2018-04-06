<?php 
include_once 'define.php';

include_once BASE_PATH . DIRECTORY_SEPARATOR . 'validateJWT.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Location</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>assets/toastr/toastr.min.css" rel="stylesheet"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var API_BASE_URL = '<?php echo API_BASE_URL; ?>';
        var BASE_URL = '<?php echo BASE_URL; ?>';
        var ASSET_URL = '<?php echo BASE_URL; ?>assets/';
    </script>
    <script src="<?php echo BASE_URL; ?>assets/main.js"></script>
    <script src="<?php echo BASE_URL; ?>assets/blockUI.js"></script>
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">

    <style>
        .dataTables_wrapper .dt-buttons {
            float: right;
        }

        .pac-container {
            z-index: 10000 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:;">Hi - <?php echo $session['data']['name'] ?></a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="javascript:;" id="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <table class="display" id="location-table" style="width:100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>CreatedAt</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>CreatedAt</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="new-location-modal" order="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form method="post" id="save-location-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Location search and save</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="fileName">Location:</label>
                            <input class="form-control" type="text" id="autocomplete" size="50"
                                   placeholder="Enter a location to search" autocomplete="on"/>
                        </div>

                        <hr>

                        <table id="address">
                            <tr>
                                <td>Street address</td>
                                <td class="slimField">
                                    <input class="field" id="street_number" disabled="true"></input>
                                </td>
                                <td class="wideField" colspan="2">
                                    <input class="field" id="route" disabled="true"></input>
                                </td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td class="wideField" colspan="3">
                                    <input class="field" id="locality" disabled="true"></input>
                                </td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td class="slimField">
                                    <input class="field" id="administrative_area_level_1" disabled="true"></input>
                                </td>
                                <td>Zip code</td>
                                <td class="wideField">
                                    <input class="field" id="postal_code" disabled="true"></input>
                                </td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td class="wideField" colspan="3">
                                    <input class="field" id="country" disabled="true"></input>
                                </td>
                            </tr>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveThisLocation()">Save</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    </div>
            </form>
        </div>

    </div>


    <script type="text/javascript">
        var locationTable, autocomplete;

        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initAutocomplete() {
            autocomplete = new google.maps.places.Autocomplete((document.getElementById('autocomplete')));
            autocomplete.addListener('place_changed', fillInAddress);
        }


        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete.getPlace();
            console.log(place);

            for (var component in componentForm) {
                document.getElementById(component).value = '';
                document.getElementById(component).disabled = false;
            }

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }
            }
        }

        function saveThisLocation() {
            var place = autocomplete.getPlace();

            var locationData = {
                'lat': place.geometry.location.lat(),
                "lng": place.geometry.location.lng(),
                "name": place.name,
                "description": place.formatted_address
            };
            $.ajax({
                type: 'POST',
                url: API_BASE_URL + '?apiName=SaveLocation',
                type: 'POST',
                data: locationData,
                dataType: 'JSON',
                beforeSend: function (request) {
                    blockUI({msg: "Saving your location, please wait..."});
                    request.setRequestHeader('jwt', getCookie('jwt'));
                },
                complete: function () {
                    unblockUI();
                    $('#save-location-form')[0].reset();
                },
                success: function (response, status, xhr) {
                    unblockUI();
                    if (response.result == 'success') {
                        locationTable.draw();
                        toastr["success"](response.message)

                    } else {
                        toastr["error"](response.message);
                    }
                    $('#new-location-modal').modal('hide');
                },
                error: function (result) {
                    var error = JSON.parse(result.responseText);
                    toastr["error"](error.message)
                    $('#new-location-modal').modal('hide');
                }
            });
        }


        $(document).ready(function () {
            var jwt = getCookie('jwt');
            locationTable = $('#location-table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ordering": false,
                "ajax": {
                    "url": API_BASE_URL + "?apiName=GetAllLocation",
                    "type": "POST",
                    beforeSend: function (request) {
                        request.setRequestHeader('jwt', jwt);
                    }
                },
                "columns": [
                    {"data": "name"},
                    {"data": "description"},
                    {"data": "lat"},
                    {"data": "lng"},
                    {"data": "createdAt"},
                    {"data": "action"}
                ]
            });

            new $.fn.dataTable.Buttons(locationTable, {
                buttons: [
                    {
                        text: 'Add New Location',
                        action: function (e, dt, node, conf) {
                            $('#new-location-modal').modal('show');
                        }
                    }
                ]
            });
            locationTable.buttons(0, null).container().prependTo(locationTable.table().container());


            $(document.body).on('click', '.delete-location', function (e) {
                e.preventDefault();
                if (confirm("Do you really want to delete this location?")) {
                    var obj = $(this);
                    $.ajax({
                        type: 'POST',
                        url: API_BASE_URL + '?apiName=DeleteLocation',
                        type: 'POST',
                        data: {id: obj.data('id')},
                        dataType: 'JSON',
                        encode: true,
                        beforeSend: function (request) {
                            blockUI({msg: "Deleting location, please wait..."});
                            request.setRequestHeader('jwt', jwt);
                        },
                        complete: function () {
                            unblockUI();
                        },
                        success: function (response, status, xhr) {
                            unblockUI();
                            if (response.result == 'success') {
                                locationTable.draw();
                            } else {
                                toastr["error"](response.message);
                            }
                        },
                        error: function (result) {
                            var error = JSON.parse(result.responseText);
                            toastr["error"](error.message);
                        }
                    });
                }
            });


        });


    </script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script
        src="http://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_KEY; ?>&libraries=places&callback=initAutocomplete"
        type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>assets/toastr/toastr.min.js"></script>

</body>
</html>