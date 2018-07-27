<?php
/**
 * Created by PhpStorm.
 * User: bhh
 * Date: 27-07-2018
 * Time: 11:54
 */

?>

                <form class="form-horizontal" method="post" id="postmandskab1">
                    <p><b>Postmandskab 1</b></p>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="team" class="control-label sr-only">Navn</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="name" placeholder="Navn">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="mobile" class="control-label sr-only">Mobil</label>
                                <div class="col-sm-12">
                                    <input type="tel" maxlength="8" name="mobile" class="form-control" placeholder="Mobil">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kreds / Gruppe</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="kreds" placeholder="Kreds / Gruppe">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kreds" class="control-label sr-only">Kommentar</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" rows="1" name="comment" placeholder="Kommentar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>