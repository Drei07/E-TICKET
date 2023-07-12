<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i> Mandatory Events</h3>
					</div>
					<button type="button" onclick="location.href='archives/course-events'" class="archives btn-dark"><i class='bx bxs-archive'></i> Archives</button>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="text" id="search-input-mandatory" placeholder="Search Events . . . . . ." class="search">
							<button class="searchBtn" type="button" onclick="searchMandatoryEvents()"><i class="bx bx-search icon"></i></button>
						</div>
						<ul class="box-info" id="mandatory-events">
							<?php
							$pdoQuery = "SELECT * FROM events WHERE course_id = :course_id AND year_level_id = :year_level_id AND event_type = :event_type AND status = :status ORDER BY id DESC";
							$pdoResult5 = $pdoConnect->prepare($pdoQuery);
							$pdoResult5->execute(array(
								":course_id" 		=> $courseId,
								":year_level_id" 	=> $yearLevelId,
								":event_type"		=> "MANDATORY",
								":status"			=> "active"
							));
							if ($pdoResult5->rowCount() >= 1) {

								while ($event_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
									extract($event_data);
							?>
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>)">

										<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event Date: <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" onclick="setSessionValues(<?php echo $event_data['id'] ?>)" class="more btn-warning">More Info</button>

									</li>
							<?php
								}
							}
							?>

							<li data-bs-toggle="modal" data-bs-target="#classModal">
								<i class='bx bxs-calendar-plus'></i>
							</li>
						</ul>
					</section>
				</div>
			</div>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-calendar'></i> Optional Events</h3>
					</div>
					<button type="button" onclick="location.href='archives/course-events'" class="archives btn-dark"><i class='bx bxs-archive'></i> Archives</button>
					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="text" id="search-input-optional" placeholder="Search Events . . . . . ." class="search">
							<button class="searchBtn" type="button" onclick="searchOptionalEvents()"><i class="bx bx-search icon"></i></button>
						</div>
						<ul class="box-info" id="optional-events">
							<?php
							$pdoQuery = "SELECT * FROM events WHERE course_id = :course_id AND year_level_id = :year_level_id AND event_type = :event_type AND status = :status ORDER BY id DESC";
							$pdoResult5 = $pdoConnect->prepare($pdoQuery);
							$pdoResult5->execute(array(
								":course_id" 		=> $courseId,
								":year_level_id" 	=> $yearLevelId,
								":event_type"		=> "OPTIONAL",
								":status"			=> "active"
							));
							if ($pdoResult5->rowCount() >= 1) {

								while ($event_data = $pdoResult5->fetch(PDO::FETCH_ASSOC)) {
									extract($event_data);
							?>
									<li onclick="setSessionValues(<?php echo $event_data['id'] ?>)">

										<img src="../../src/img/<?php echo $event_data['event_poster'] ?>" alt="">
										<h4><?php echo $event_data['event_name'] ?></h4>
										<p>Event Date <?php echo date('m/d/y', strtotime($event_data['event_date'])); ?></p>
										<button type="button" onclick="setSessionValues(<?php echo $event_data['id'] ?>)" class="more btn-warning">More Info</button>

									</li>
							<?php
								}
							}
							?>

							<li data-bs-toggle="modal" data-bs-target="#classModal">
								<i class='bx bxs-calendar-plus'></i>
							</li>
						</ul>
					</section>
				</div>
			</div>