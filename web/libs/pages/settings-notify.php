<div class="tab-pane" id="notify">
    <div class="container">
        <div class="row">
            <!-- Team Profile -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-text card-header-info">
                        <div class="card-text">
                            <h4 class="card-title">Notifications</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover table-rwd">
                                    <thead class="text-info">
                                        <tr class="tr-only-hide">
                                            <th>Message</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                                if(!empty($_SESSION['steamid'])){
                                                    $input = array(
                                                        ":id" => $_SESSION['steamid'],
                                                    );
                                                    $sql = "SELECT * FROM ".$notify_table." WHERE receive = :id AND status = ''";
                                                    $sth = $pdo->prepare($sql);
                                                    $sth->execute($input);
                                                    $result = $sth->fetchAll();

                                                    $sth = $pdo->prepare($sql);
                                                    $sth->execute($input);
                                                    $steamids = $sth->fetchAll(PDO::FETCH_COLUMN, 1);
                                                    $data = SteamData::GetData($SteamAPI_Key, $steamids);
                    
                                                    foreach($result as $row)
                                                    {
                                                        switch($row["type"])
                                                        {
                                                            case "invite":
                                                                    ?>
                                                                        <td data-th="Message">
                                                                            <a href="http://steamcommunity.com/profiles/<?=$row["send"]?>"><?=$data["name"][ $row["send"]] ?></a>
                                                                            invites you to join his/her team!
                                                                        </td>
                                                                        <td data-th="Action">
                                                                            <a href="#" class="text-success mx-2" onclick="accept();">
                                                                                <i class="fas fa-check"></i>
                                                                            </a>
                                                                            <a href="#" class="text-danger mx-2" onclick="deny();">
                                                                                <i class="fas fa-times"></i>
                                                                            </a>
                                                                        </td>
                                                                    <?php
                                                                break;
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ml-auto mr-auto"></div>
                </div>
            </div>
        </div>
    </div>
</div>