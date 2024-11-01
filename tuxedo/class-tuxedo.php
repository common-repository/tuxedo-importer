<?php

namespace Tuxedo_Importer\Tuxedo;

class Tuxedo_API
{

	const TIMETOLIVE = 290;
	const BASE_URL = 'https://api.tuxedoticket.ca/v1/';
	// const BASE_URL = 'http://host.docker.internal:9510/v1/';

	/**
	 * Tuxedo account name
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_account_name;

	/**
	 * Tuxedo username
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_username;

	/**
	 * Tuxedo password
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_password;

	/**
	 * Tuxedo Channel
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_channel;

	/**
	 * Tuxedo Channels
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_channels;

	/**
	 * Tuxedo Channels
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_channels_price_categories = [];

	/**
	 * Tuxedo Price Categories
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_price_categories;

	/**
	 * Tuxedo Download Images
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_download_images;

	/**
	 * Tuxedo Code
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_code;

	/**
	 * Tuxedo Code
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_last_authenticated;

	/**
	 * Shows Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_shows;

	/**
	 * Events Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_events;

	/**
	 * Venues Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_venues;

	/**
	 * Series Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_series;

	/**
	 * Report Categories Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_report_categories;

	/**
	 * Results Array
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_results;


	/**
	 * Start time
	 *
	 * @since  0.0.1
	 * @access protected
	 */
	protected $tuxedo_api_start_time;


    /**
     * Post id to import
     *
     * @since 1.1.2
     * @access protected
     */
    protected $tuxedo_api_post_id;

	/**
	 * Construct method
	 *
	 * @since 0.0.1
	 */
	public function __construct()
	{
		// Saves the plugin parameters in class properties
		$options = get_option('tuxedo_importer_plugin_options');
		$this->tuxedo_api_account_name = $options['account_name'];
		$this->tuxedo_api_username = $options['username'];
		$this->tuxedo_api_password = $options['password'];
		$this->tuxedo_api_download_images =  $options['image'] ?? false;

		// Support both array-style channels or the old comma-delimited channel format
		$channel = $options['channel'] ?? array();
		if (is_string($channel)) {
			$channel = explode(',', str_replace(' ', '', $channel));
		}

		$this->tuxedo_api_channel = $channel;

		//Obtains a connection token from the Tuxedo API to be used later
		$this->authenticate();
	}

	/**
	 * Obtains a connection token from the Tuxedo API to be used later (valid for 5 minutes)
	 */
	private function authenticate()
	{

		$args = array(
			'accountName' => $this->tuxedo_api_account_name,
			'password'    => $this->tuxedo_api_password,
			'username'    => $this->tuxedo_api_username
		);
		$results = $this->request("authentication", "POST", json_encode($args));
		if ($results != false) {
			$this->tuxedo_api_code = json_decode($results)->jwt;
			$this->tuxedo_api_last_authenticated = time();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Makes a request to the Tuxedo API
	 */
	public function request($endpoint, $action, $json = false)
	{
		/*if (!empty($this->tuxedo_api_last_authenticated) && time() -  $this->tuxedo_api_last_authenticated >= $this::TIMETOLIVE) {
			if (!$this->authenticate()) {
                $this->log("Authentication failed");
                return false;
            }
		}*/

		$args = array(
			'headers' => array(
                'Authorization' => 'Bearer ' . $this->tuxedo_api_code,
				'Content-Type' => 'application/json',
				'Accept' => 'application/json'
			),
		);

		if ($action == "POST") {
            $args['body'] = $json;
			$response = wp_remote_post($this::BASE_URL . $endpoint, $args);
		} elseif ($action == "GET") {
            $response = wp_remote_get($this::BASE_URL . $endpoint, $args);
		}

		/*if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
			$this->log("Something went wrong: $error_message");
			return false;
		} else {
            $response_code = wp_remote_retrieve_response_code($response);
			if ($response_code == 401 && $endpoint != "authentication") {
                $this->authenticate();
                return $this->request($endpoint, $action, $json);

			} elseif ($response_code != 200 && $response_code != 201) {
				$this->log("Something went wrong: $response_code");
				return false;
			}
		}*/

		return $response['body'];
	}

	/**
	 * Runs all the tasks to import the content from the Tuxedo API
	 */
	public function run()
	{
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		ini_set('max_execution_time', 3600);
		if (empty($this->tuxedo_api_account_name) || empty($this->tuxedo_api_username) || empty($this->tuxedo_api_password)) {
			return null;
		}

        do_action( 'tuxedo_before_import');

		$this->tuxedo_api_start_time = time();
		$this->emptyLog();
		$this->log('<div class="tuxedo--logs"><h2>Importation commencée le ' .  wp_date('j F Y H:i:s') . '</h2>');
		$this->tuxedo_api_channels = $this->getAndPrepareChannels();
		$this->tuxedo_api_price_categories = $this->getAndPreparePriceCategories();
		$this->tuxedo_api_venues = $this->getAndPrepareVenues();
		$this->tuxedo_api_series = $this->getAndPrepareSeries();
		$this->tuxedo_api_shows = $this->getAndPrepareShows();
		$this->tuxedo_api_events = $this->getAndPrepareEvents();
		$this->tuxedo_api_shows = $this->filterShowsByChannel($this->tuxedo_api_shows);
		$this->tuxedo_api_series = $this->getAndPrepareSeries();
		$this->tuxedo_api_report_categories = $this->getAndPrepareReportCategories();
        $this->tuxedo_api_event_price_categories = $this->getAndPrepareEventPriceCategories();
		$this->processData();
		$this->logResults();
		$this->log("<h2>Importation complétée le " . wp_date('j F Y H:i:s') . '</h2></div>@');

        do_action('tuxedo_after_import');
	}

	/**
	 *
	 * Call the Tuxedo API to get all shows and puts them in an array with the tuxedo ID as the index.
	 * Also creates an empty array to store the events (représentations) for that show.
	 *
	 * @return  array   an array of show objects
	 *
	 */
	public function getAndPrepareShows()
	{
		$shows = array();
		//file_put_contents(__DIR__ . '/shows.json', $this->request("shows", "GET"));
		foreach (json_decode($this->request("shows", "GET")) as $show) {
			//Ajoute l'object a un array avec l'ID comme index
			$shows[$show->id] = $show;

			//Créer un array vide pour recevoir les events  plus bas
			$shows[$show->id]->events = array();
		}
		return $shows;
	}

	/**
	 *
	 * Call the Tuxedo API to get all events and puts them in an array with the tuxedo ID as the index.
	 * Also adds (pushes) the REFERENCE of the indiviual event object to the events array for the parent show im the shows array
	 *
	 * @return  array   an array of event objects
	 *
	 */
	public function getAndPrepareEvents()
	{
		$events = array();
		foreach (json_decode($this->request("events", "GET")) as $event) {
			$event->venueName = $this->tuxedo_api_venues[$event->venueId]->name;
			//Ajoute l'object a un array avec l'ID comme index
			$events[$event->id] = $event;

			//Ajout des events a l'object show
			array_push($this->tuxedo_api_shows[$event->showId]->events, $event);
		}
		return $events;
	}

	/**
	 *
	 * Call the Tuxedo API to get all price categories and puts them in an array with the tuxedo ID as the index.
	 *
	 * @return  array   an array of price categories objects
	 *
	 */
	public function getAndPreparePriceCategories()
	{
		$categories = array();
		foreach (json_decode($this->request("priceCategories", "GET")) as $category) {
			$categories[$category->id] = $category;
            if (!isset($category->channels)) continue;
            foreach ($category->channels as $channel_id) {
                array_push($this->tuxedo_api_channels[$channel_id]->price_categories, $category);
            }
		}
		return $categories;
	}

    public function getAndPrepareChannels()
	{
		$channels = array();
		foreach (json_decode($this->request("channels", "GET")) as $channel) {
            $channel->price_categories = array();
			$channels[$channel->id] = $channel;
		}
		return $channels;
	}

	/**
	 *
	 * Call the Tuxedo API to get all venues and puts them in an array with the tuxedo ID as the index.
	 *
	 * @return  array   an array of venue objects
	 *
	 */
	public function getAndPrepareVenues()
	{
		$venues = array();
		foreach (json_decode($this->request("venues", "GET")) as $venue) {
			$venues[$venue->id] = $venue;
		}
		return $venues;
	}

	/**
	 *
	 * Call the Tuxedo API to get all categories and puts them in an array with the tuxedo ID as the index.
	 *
	 * @return  array   an array of categories objects
	 *
	 */
	public function getAndPrepareSeries()
	{
		$series = array();
		foreach (json_decode($this->request("showCategories", "GET")) as $serie) {
			$series[$serie->id] = $serie;
		}
		return $series;
	}

	/**
	 *
	 * Call the Tuxedo API to get all report categories and puts them in an array with the tuxedo ID as the index.
	 *
	 * @return  array   an array of report categories objects
	 *
	 */
	public function getAndPrepareReportCategories()
	{
		$series = array();
		foreach (json_decode($this->request("reportCategories", "GET")) as $serie) {
			$series[$serie->id] = $serie;
		}
		return $series;
	}

    /**
	 *
	 * Call the Tuxedo API to get all event price categories and puts them in an array with the tuxedo ID as the index.
	 *
	 * @return  array   an array of event price categories objects
	 *
	 */
	public function getAndPrepareEventPriceCategories()
	{
		$prices = array();
		foreach (json_decode($this->request("eventPriceCategories", "GET")) as $price) {
            $event_id = $price->eventId;
            $show_id = $this->tuxedo_api_events[$event_id]->showId;
            $channels = [];
            if (isset($this->tuxedo_api_shows[$show_id]->channels)) {
                $channels = array_map(function($channel) {
                    return $channel->id;
                }, $this->tuxedo_api_shows[$show_id]->channels);
            }
            $channels = array_intersect($channels, $this->tuxedo_api_channel);
            if (empty($channels)) continue;
            foreach ($channels as $channel_id) {
                if (!isset($this->tuxedo_api_price_categories[$price->priceCategoryId])) continue;
                if (!isset($prices[$event_id])) $prices[$event_id] = array();
                array_push($prices[$event_id], $price);
            }
		}
		return $prices;
	}

	/**
	 *
	 * Takes the unfiltered master spectacles/représentations array and removes the shows not currently in the selected Tuxedo channel
	 *
	 * @param   array    $shows The unfiltered master spectacles/représentations array
	 * @return  array    an array of show objects
	 *
	 */
	public function filterShowsByChannel($shows)
	{
		foreach ($shows as $id => $show) {
			$keep = false;
			if (isset($show->channels)) {
				foreach ($show->channels as $channel) {
					if (empty($this->tuxedo_api_channel) || in_array($channel->id, $this->tuxedo_api_channel)) {
                        $keep = true;
                        // Temporairement désactivé
						/*if (isset($channel->start) && strtotime($channel->start) < time()) {
							if (isset($channel->end)) {
								if (strtotime($channel->end) > time()) {
									$keep = true;
								}
							} else {
								$keep = true;
							}
						}*/
					}
				}
			}
			if (!$keep) {
				unset($shows[$id]);
			}
		}
		return $shows;
	}

	/**
	 *
	 * Creates or updates the shows and the events
	 *
	 */
	public function processData()
	{
        $this->tuxedo_api_results = array('channels' => array(0, 0, 0, 0, 0), 'shows' => array(0, 0, 0, 0, 0), 'events' => array(0, 0, 0, 0, 0));
        foreach ($this->tuxedo_api_channels as $channel) {
            $this->tuxedo_api_results['channels'][$this->createOrUpdateChannel($channel)]++;
        }
        $this->tuxedo_api_results['channels'][4] += $this->deleteMissingChannels();
		foreach ($this->tuxedo_api_shows as $show) {
			$this->tuxedo_api_results['shows'][$this->createOrUpdateShow($show)]++;
		}
		$this->tuxedo_api_results['shows'][4] += $this->deleteMissingShows();
		foreach ($this->tuxedo_api_events as $event) {
			$this->tuxedo_api_results['events'][$this->createOrUpdateEvent($event)]++;
		}
		$this->tuxedo_api_results['events'][4] += $this->deleteMissingEvents();
		$this->tuxedo_api_results['events'][4] += $this->deletePassedEvents();
	}

	/**
	 *
	 * Creates or updates the show
	 * @param object $show The show object
	 * @return int 0 = failed, 1 = updated, 2 = created, 3 = ignored, 4 = deleted
	 */
	public function createOrUpdateShow(&$show)
	{
		$return_value = 0;
		$existing = $this->get_post_by_tuxedo_id('tuxedo-show', $show->id);
		$tuxedo_modified_date = $show->modifiedOn ?? "Jan 01 1970 00:00:00 GMT+0000";
		$tuxedo_modified_date = strtotime($tuxedo_modified_date);

		$fields = [
			'post_title' => $show->title->french ?? null,
			'post_type' => 'tuxedo-show',
			'post_content' => "",
			'post_status' => 'publish'
		];

		if ($existing) {
			$fields['ID'] = $existing;
			$return_value = 1;
		} else {
			$return_value = 2;
		}

		$post_id = wp_insert_post($fields);

		update_field('tuxedo_id', $show->id, $post_id);
		update_field('show_title_en', $show->title->english ?? null, $post_id);
		update_field('show_last_seen', time(), $post_id);
		update_field('show_next_event', "", $post_id);
		update_field('show_next_event_date', null, $post_id);
		update_field('show_next_event_venue', "", $post_id);
		update_field('show_is_sold_out', null, $post_id);

        /*$channel_ids = array_map(function($channel) {
            return $channel->id;
        }, $show->channels);
        $channel_ids = array_intersect($channel_ids, $this->tuxedo_api_channel);*/
        //update_field('channel_ids', $channel_ids, $post_id);
        update_field('channels', $show->channels, $post_id);

        /*if ($post_id == 339) {
            \file_put_contents(__DIR__ . '/test.json', json_encode($show));
        }*/

        $draft = true;
        foreach ($show->channels as $channel) {
            if (!isset($channel->start) || strtotime($channel->start) <= time()) {
                $draft = false;
            }
        }

        $status = get_post_status($post_id);
        if ($draft && $status != 'draft') {
            wp_update_post([
                'ID' => $post_id,
                'post_status' => 'draft'
            ]);
        } elseif (!$draft && $status != 'publish') {
            wp_update_post([
                'ID' => $post_id,
                'post_status' => 'publish'
            ]);
        }

		// If the show doesnt have any new changes in Tuxedo, then skip the rest of the import process
		if ($tuxedo_modified_date < get_field('show_modified_date', $post_id)) {
			return 3;
		}

		if (isset($show->categories[0])) {
			$categories = array();
			$categories_en = array();
			foreach ($show->categories as $category) {
				if (isset($this->tuxedo_api_series[$category], $this->tuxedo_api_series[$category]->name, $this->tuxedo_api_series[$category]->name->french)) $categories[] = $this->tuxedo_api_series[$category]->name->french;
				if (isset($this->tuxedo_api_series[$category], $this->tuxedo_api_series[$category]->name, $this->tuxedo_api_series[$category]->name->english)) $categories_en[] = $this->tuxedo_api_series[$category]->name->english;
			}
			update_field('show_categories', implode(', ', $categories), $post_id);
			update_field('show_categories_en', implode(', ', $categories_en), $post_id);
		} else {
			update_field('show_categories', "", $post_id);
			update_field('show_categories_en', "", $post_id);
		}
		if (isset($show->reportCategoryId1) && isset($this->tuxedo_api_report_categories[$show->reportCategoryId1]->name)) {
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId1]->name->french)) update_field('show_report_categories_one', $this->tuxedo_api_report_categories[$show->reportCategoryId1]->name->french, $post_id);
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId1]->name->english)) update_field('show_report_categories_one_en', $this->tuxedo_api_report_categories[$show->reportCategoryId1]->name->english, $post_id);
		} else {
			update_field('show_report_categories_one', "", $post_id);
			update_field('show_report_categories_one_en', "", $post_id);
		}
		if (isset($show->reportCategoryId2) && isset($this->tuxedo_api_report_categories[$show->reportCategoryId2]->name)) {
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId2]->name->french)) update_field('show_report_categories_two', $this->tuxedo_api_report_categories[$show->reportCategoryId2]->name->french, $post_id);
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId2]->name->english)) update_field('show_report_categories_two_en', $this->tuxedo_api_report_categories[$show->reportCategoryId2]->name->english, $post_id);
		} else {
			update_field('show_report_categories_two', "", $post_id);
			update_field('show_report_categories_two_en', "", $post_id);
		}
		if (isset($show->reportCategoryId3) && isset($this->tuxedo_api_report_categories[$show->reportCategoryId3]->name)) {
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId3]->name->french)) update_field('show_report_categories_three', $this->tuxedo_api_report_categories[$show->reportCategoryId3]->name->french, $post_id);
			if (isset($this->tuxedo_api_report_categories[$show->reportCategoryId3]->name->english)) update_field('show_report_categories_three_en', $this->tuxedo_api_report_categories[$show->reportCategoryId3]->name->english, $post_id);
		} else {
			update_field('show_report_categories_three', "", $post_id);
			update_field('show_report_categories_three_en', "", $post_id);
		}

		foreach (array('1', '2', '3', '4', '5', '6', '7', '8') as $i) {
			$image_id = get_field('show_image_' . $i, $post_id);
			$url = $image_id ? get_field('original_url', $image_id) : null;
			if ($this->tuxedo_api_download_images && isset($show->images) && isset($show->images->misc->{'image' . $i}) && $show->images->misc->{'image' . $i}!=$url) {
				try {
					$file = array();
					$file['name'] = "tuxedo-image-" . sanitize_title($show->title->french) . "." . pathinfo($show->images->misc->{'image' . $i}, PATHINFO_EXTENSION);
					$file['tmp_name'] = download_url($show->images->misc->{'image' . $i});

					$attachmentId = media_handle_sideload($file, $post_id);
					update_field('show_image_' . $i, $attachmentId ?? null, $post_id);
					if ($attachmentId) update_field('original_url', $show->images->misc->{'image' . $i}, $attachmentId);
				} catch (Exception $e) {
					return 0;
				}
			} else if (get_field('show_image_' . $i, $post_id) && (!isset($show->images) || !$show->images->misc->{'image' . $i})) {
				update_field('show_image_' . $i, null, $post_id);
			}
		}

		update_field('show_modified_date', time(), $post_id);

		update_field('misc_value_1', $show->misc->value1 ?? "", $post_id);
		update_field('misc_value_2', $show->misc->value2 ?? "", $post_id);
		update_field('misc_value_3', $show->misc->value3 ?? "", $post_id);

		update_field('show_subtitle', $show->subtitles[0]->french ?? "", $post_id);
		update_field('show_subtitle_en', $show->subtitles[0]->english ?? "", $post_id);
		update_field('show_description', $show->description->french ?? "", $post_id);
		update_field('show_description_en', $show->description->english ?? "", $post_id);
		update_field('show_tag', $show->tag->french ?? "", $post_id);
		update_field('show_tag_en', $show->tag->english ?? "", $post_id);
		update_field('show_distribution', $show->secondaryDescription->french ?? "", $post_id);
		update_field('show_distribution_en', $show->secondaryDescription->english ?? "", $post_id);
		update_field('show_duration', $show->duration ?? "", $post_id);
		update_field('show_link', $show->tuxedoUrl ?? "", $post_id);

        if (isset($show->externalUrl)) {
            update_field('show_external_link', $show->externalUrl->url ?? "", $post_id);
        } else {
            update_field('show_external_link', "", $post_id);
        }

        if (isset($show->videos) && is_array($show->videos)) {
            update_field('show_video_link', $show->videos[0] ?? "", $post_id);
        } else {
            update_field('show_video_link', "", $post_id);
        }

        do_action('tuxedo_after_show_import', $post_id, $show);

		return $return_value;
	}

	/**
	 *
	 * Creates or updates the event
	 * @param object $show The event object
	 * @return int 0 = failed, 1 = updated, 2 = created, 3 = ignored
	 */
	public function createOrUpdateEvent(&$event)
	{
		$baseDate = "Jan 01 1970 00:00:00 GMT+0000";
		$return_value = 0;
		$existing = $this->get_post_by_tuxedo_id('tuxedo-event', $event->id);
		$tuxedo_modified_date = $event->modifiedOn ?? $baseDate;
		$tuxedo_modified_date = strtotime($tuxedo_modified_date);

		$show = $this->get_post_by_tuxedo_id('tuxedo-show', $event->showId);
		$eventDate = strtotime($event->date ?? $baseDate);

		if (!$show || $eventDate < time()) {
			return 3;
		}

		$fields = [
			'post_title' => get_the_title($show) . ' - ' . wp_date('j F Y H:i', $eventDate),
			'post_type' => 'tuxedo-event',
			'post_content' => "",
			'post_status' => 'publish'
		];

		if ($existing) {
			$fields['ID'] = $existing;
			$return_value = 1;
		} else {
			// create post
			$return_value = 2;
		}

		$post_id = wp_insert_post($fields);

		update_field('tuxedo_id', $event->id, $post_id);
		update_field('event_last_seen', time(), $post_id);

		$currentShowNextEvent = get_field('show_next_event', $show);

		if (empty($currentShowNextEvent) || $eventDate < get_field('event_datetime', $currentShowNextEvent)) {
			update_field('show_next_event', $post_id, $show);
			update_field('show_next_event_venue', $event->venueName, $show);
			update_field('show_next_event_date', $eventDate, $show);
		}

		$isShowAllSoldOut = get_field('show_is_sold_out', $show);
		if ($isShowAllSoldOut != "non-complet") {
			if (isset($event->isSoldOut) && $event->isSoldOut) {
				update_field('show_is_sold_out', "complet", $show);
			} else {
				update_field('show_is_sold_out', "non-complet", $show);
			}
		}

		update_field('event_is_sold_out', $event->isSoldOut ?? false, $post_id);

        $updated = false;
        if (isset($this->tuxedo_api_event_price_categories[$event->id])) {
            $prices = [];
            $priceIds = [];
            foreach ($this->tuxedo_api_event_price_categories[$event->id] as $price) {
                if (in_array($price->priceCategoryId, $priceIds)) continue;
                if (!isset($this->tuxedo_api_channels_price_categories[$price->priceCategoryId]) || !$this->tuxedo_api_channels_price_categories[$price->priceCategoryId]) continue;
                $prices[] = [
                    'category_id' => $price->priceCategoryId,
                    'category' => $this->tuxedo_api_price_categories[$price->priceCategoryId]->name->french ?? "",
                    'category_en' => $this->tuxedo_api_price_categories[$price->priceCategoryId]->name->english ?? "",
                    'price' => $price->amount/100
                ];
                $priceIds[] = $price->priceCategoryId;
            }
            update_field('event_price_categories', $prices, $post_id);
            $updated = true;
        } else {
            update_field('event_price_categories', [], $post_id);
        }

		// If the event doesnt have any new changes in Tuxedo, then skip the rest of the import process
		if ($tuxedo_modified_date < get_field('event_modified_date', $post_id) && !$updated) {
			return 3;
		}
		update_field('event_show', $show, $post_id);
		update_field('event_modified_date', time(), $post_id);

		if (isset($event->status) && in_array($event->status, array("cancelled", "none", "postponed", "suspended", "soldOut"))) {
			update_field('event_status', $event->status, $post_id);
		} else {
			update_field('event_status', "", $post_id);
		}

		update_field('event_datetime', $eventDate, $post_id);
		update_field('event_venue', $event->venueName, $post_id);
		update_field('event_link', $event->tuxedoUrl ?? "", $post_id);

        do_action('tuxedo_after_event_import', $post_id, $event);

		return $return_value;
	}

    /**
     * Create or update channel cpt
     */
    public function createOrUpdateChannel(&$channel)
    {
        $return_value = 0;
        $existing = $this->get_post_by_tuxedo_id('tuxedo-channel', $channel->id);
        if (!in_array($channel->id, $this->tuxedo_api_channel)) return;

        $tuxedo_modified_date = $channel->modifiedOn ?? "Jan 01 1970 00:00:00 GMT+0000";
        $tuxedo_modified_date = strtotime($tuxedo_modified_date);

        $fields = [
            'post_title' => $channel->name,
            'post_type' => 'tuxedo-channel',
            'post_content' => "",
            'post_status' => 'publish'
        ];
        if ($existing) {
            $fields['ID'] = $existing;
            $return_value = 1;
        } else {
            $return_value = 2;
        }
        $post_id = wp_insert_post($fields);

        update_field('tuxedo_id', $channel->id, $post_id);
        update_field('channel_last_seen', time(), $post_id);

        $categories = get_field('price_categories', $post_id) ?: [];
        foreach ($channel->price_categories as $category) {
            $exist = false;
            foreach ($categories as $c) {
                if ($category->id == $c['tuxedo_id']) $exist = true;
            }

            if ($exist) continue;
            if (!isset($category->channels) || !in_array($channel->id, $category->channels)) continue;
            $categories[] = [
                'tuxedo_id' => $category->id,
                'name' => $category->name->french,
                'name_en' => $category->name->english,
                'show_category' => true,
                'restriction' => $category->restriction ?? '',
            ];
        }
        update_field('price_categories', $categories, $post_id);
        foreach ($categories as $cat) {
            $this->tuxedo_api_channels_price_categories[$cat['tuxedo_id']] = $cat['show_category'];
        }

        if ($tuxedo_modified_date < get_field('channel_modified_date', $post_id)) {
            return;
        }

        update_field('channel_name', $channel->name, $post_id);
        update_field('channel_modified_date', time(), $post_id);

        return $return_value;
    }

	/**
	 * Try to get post based on its slug/name value using the generated UUID
	 */
	private function get_post_by_tuxedo_id($type, $tuxedo_id)
	{
		$args = array(
			'post_type' => $type,
			'posts_per_page' => 1,
			'meta_key'        => 'tuxedo_id',
			'meta_value'    => $tuxedo_id,
            'post_status' => ['publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash']
		);

		$posts = get_posts($args);

		return isset($posts[0]) ? $posts[0]->ID : null;
	}

	/**
	 * Deletes the show and its image
	 */
	private function deleteShow($show_id)
	{
		$this->deleteImageByShowId($show_id);
		$this->deleteAllEventsByShowId($show_id);
		wp_delete_post($show_id, true);
	}

	/**
	 * Deletes the channel
	 */
	private function deleteChannel($channel_id)
	{
		wp_delete_post($channel_id, true);
	}

	/**
	 * Deletes the event
	 */
	private function deleteEvent($event_id)
	{
		wp_delete_post($event_id, true);
	}

	/**
	 * Deletes an image found by the show id
	 */
	private function deleteImageByShowId($show_id)
	{
		foreach (array('1', '2', '3', '4', '5', '6', '7', '8') as $i) {
			$image = get_field('show_image_' . $i, $show_id);
			if ($image) {
				wp_delete_attachment($image, true);
			}
		}
	}

	/**
	 * Delete all images
	 */
	public function deleteAllImages()
	{
		$shows = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-show',
			)
		);
		foreach ($shows as $show) {
			$this->deleteImageByShowId($show->ID);
		}
	}

	/**
	 * Delete all shows
	 */
	public function deleteAllShows()
	{
		$shows = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-show',
			)
		);
		foreach ($shows as $show) {
			$this->deleteShow($show->ID);
		}
	}

	/**
	 * Delete all events
	 */
	public function deleteAllEvents()
	{
		$events = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-event',
			)
		);
		foreach ($events as $event) {
			$this->deleteEvent($event->ID);
		}
	}

	/**
	 * Delete all events
	 */
	public function deleteAllEventsByShowId($show_id)
	{
		$events = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-event',
				'meta_query' => array(
					array(
						'key'  => 'event_show',
						'compare'   => '=',
						'value'     => $show_id,
					),
				),
			)
		);
		foreach ($events as $event) {
			$this->deleteEvent($event->ID);
		}
	}

	private function log($string)
	{
		// error_log("****************************************************************");
		// error_log(__FILE__);
		// error_log($file);
		file_put_contents(dirname(__FILE__) . '/logs/tuxedo-importer.txt', $string . PHP_EOL, FILE_APPEND | LOCK_EX);
		$transient = get_transient("tuxedo_importer_logs");
		$transient =  $transient . $string;
		set_transient("tuxedo_importer_logs", $transient);
	}

	private function emptyLog()
	{
		delete_transient("tuxedo_importer_logs");
	}

	private function logResults()
	{
		$message = '<div class="tuxedo--logs__wrapper"><h3>Importation des spectacles</h3><div class="tuxedo--logs__body"><p>' . PHP_EOL .
			$this->tuxedo_api_results['shows'][0] . " échecs</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['shows'][1] . " spectacles modifiés</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['shows'][2] . " spectacles créés</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['shows'][3] . " spectacles non modifiés</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['shows'][4] . " spectacles supprimés</p></div></div>" . PHP_EOL;


		$message .= '<div class="tuxedo--logs__wrapper"><h3>Importation des représentations</h3><div class="tuxedo--logs__body"><p>' . PHP_EOL .
			$this->tuxedo_api_results['events'][0] . " échecs</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['events'][1] . " représentations modifiées</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['events'][2] . " représentations créées</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['events'][3] . " représentations non modifiées ou créées</p><p>" . PHP_EOL .
			$this->tuxedo_api_results['events'][4] . " représentations supprimées</p></div></div>" . PHP_EOL;
		$this->log($message);
	}


	/**
	 * Delete shows that are not in tuxedo anymore
	 */
	public function deleteMissingShows()
	{
		$shows = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-show',
				'meta_query' => array(
					array(
						'key'  => 'show_last_seen',
						'compare'   => '<=',
						'value'     => $this->tuxedo_api_start_time - 30,
					),
				),
			)
		);
		foreach ($shows as $show) {
			$this->deleteShow($show->ID);
		}
		return count($shows);
	}

	/**
	 * Delete channels that are not selected anymore
	 */
	public function deleteMissingChannels()
	{
        $options = get_option('tuxedo_importer_plugin_options');
        $selectedChannels =  $options['channel'];
        if (!is_array($selectedChannels)) $selectedChannels = explode(',', $selectedChannels);
		$channels = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-channel',
				'meta_query' => array(
					array(
						'key'  => 'tuxedo_id',
						'compare'   => 'NOT IN',
						'value'     => $selectedChannels,
					),
				),
			)
		);
		foreach ($channels as $channel) {
			$this->deleteChannel($channel->ID);
		}
		return count($channels);
	}

	/**
	 * Delete events that are not in tuxedo anymore
	 */
	public function deleteMissingEvents()
	{
		$events = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-event',
				'meta_query' => array(
					array(
						'key'  => 'event_last_seen',
						'compare'   => '<=',
						'value'     =>  $this->tuxedo_api_start_time - 30,
					),
				),
			)
		);
		foreach ($events as $event) {
			$this->deleteEvent($event->ID);
		}
		return count($events);
	}

	/**
	 * Delete events that have happened in the past
	 */
	public function deletePassedEvents()
	{
		$events = get_posts(
			array(
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => 'tuxedo-event',
				'meta_query' => array(
					array(
						'key'  => 'event_datetime',
						'compare'   => '<=',
						'value'     => time(),
					),
				),
			)
		);
		foreach ($events as $event) {
			$this->deleteEvent($event->ID);
		}
		return count($events);
	}

    public function upgrade($saved_version)
    {
        if (!$saved_version || $saved_version<'1.0.9') {
            add_action('init', array($this, 'migrate109'));
        }
    }

    public function migrate109()
    {
        $this->log("Migration for 1.0.9");

        if (empty($this->tuxedo_api_account_name) || empty($this->tuxedo_api_username) || empty($this->tuxedo_api_password)) {
            return null;
        }
        $this->tuxedo_api_channels = $this->getAndPrepareChannels();
        $this->tuxedo_api_price_categories = $this->getAndPreparePriceCategories();
        if (empty($this->tuxedo_api_channels)) return;

        foreach ($this->tuxedo_api_channels as $channel) {
            $this->createOrUpdateChannel($channel);
        }

        $options = get_option('tuxedo_importer_plugin_options');
        $domain = $options['domain'];
        $selectedChannels =  $options['channel'];
        if (empty($domain) || empty($selectedChannels)) return;
        foreach ($selectedChannels as $tuxedo_id) {
            $args = [
                'post_type' => 'tuxedo-channel',
                'posts_per_page' => 1,
                'meta_field' => 'tuxedo_id',
                'meta_value' => $tuxedo_id,
                'fields' => 'ids'
            ];
            $query = new \WP_Query($args);
            if ($query->have_posts()) {
                $channel = $query->posts[0];
                update_field('url_ticket', $domain, $channel);
            }
        }
    }

    public function updateChannels()
    {
        if (empty($this->tuxedo_api_account_name) || empty($this->tuxedo_api_username) || empty($this->tuxedo_api_password)) {
            return null;
        }
        $this->tuxedo_api_channels = $this->getAndPrepareChannels();
        $this->tuxedo_api_price_categories = $this->getAndPreparePriceCategories();
        if (empty($this->tuxedo_api_channels)) return;

        foreach ($this->tuxedo_api_channels as $channel) {
            $this->createOrUpdateChannel($channel);
        }

        $this->deleteMissingChannels();
    }

    public function single_import($post_id)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		ini_set('max_execution_time', 3600);
		if (empty($this->tuxedo_api_account_name) || empty($this->tuxedo_api_username) || empty($this->tuxedo_api_password)) {
			return null;
		}

        do_action( 'tuxedo_before_import');

		$this->tuxedo_api_channels = $this->getAndPrepareChannels();
		$this->tuxedo_api_price_categories = $this->getAndPreparePriceCategories();
		$this->tuxedo_api_venues = $this->getAndPrepareVenues();
		$this->tuxedo_api_series = $this->getAndPrepareSeries();
		$this->tuxedo_api_shows = $this->getAndPrepareShows();
		$this->tuxedo_api_events = $this->getAndPrepareEvents();
		$this->tuxedo_api_shows = $this->filterShowsByChannel($this->tuxedo_api_shows);
		$this->tuxedo_api_series = $this->getAndPrepareSeries();
		$this->tuxedo_api_report_categories = $this->getAndPrepareReportCategories();
        $this->tuxedo_api_event_price_categories = $this->getAndPrepareEventPriceCategories();

        $single_import = true;
        $post_type = get_post_type($post_id);

        foreach ($this->tuxedo_api_channels as $channel) {
            $this->createOrUpdateChannel($channel);
        }

        if ($post_type == 'tuxedo-show') {
            $this->tuxedo_api_shows = array_filter($this->tuxedo_api_shows, function($show) use ($post_id) {
                return $show->id == get_field('tuxedo_id', $post_id);
            });
            $this->tuxedo_api_events = array_filter($this->tuxedo_api_events, function($event) use ($post_id) {
                return $event->showId == get_field('tuxedo_id', $post_id);
            });
        } elseif ($post_type == 'tuxedo-event') {
            $this->tuxedo_api_events = array_filter($this->tuxedo_api_events, function($event) use ($post_id) {
                return $event->id == get_field('tuxedo_id', $post_id);
            });
        }

        if ($post_type == 'tuxedo-show') {
            foreach ($this->tuxedo_api_shows as $show) {
                $this->createOrUpdateShow($show);
            }
        }
        foreach ($this->tuxedo_api_events as $event) {
            $this->createOrUpdateEvent($event);
        }

        do_action('tuxedo_after_import');
    }
}
