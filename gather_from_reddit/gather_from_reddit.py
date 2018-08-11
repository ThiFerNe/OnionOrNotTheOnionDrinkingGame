#!/bin/usr/env python

import argparse
import requests
import json


def parse_args():
    parser = argparse.ArgumentParser(description='')
    parser.add_argument(
        '--type',
        '-t',
        action='store',
        choices=[
            'hour',
            'day',
            'week',
            'month',
            'year',
            'all'
        ],
        default='all',
        type=str,
        help='',
        dest='type'
    )
    parser.add_argument(
        '-s',
        '--subredditname',
        action='store',
        default='TheOnion',
        type=str,
        help='',
        dest='subredditname'
    )
    parser.add_argument(
        '-c',
        '--count',
        action='store',
        default=25,
        type=int,
        help='',
        dest='count'
    )
    parser.add_argument(
        '-o',
        '--output',
        action='store',
        default='output.json',
        type=str,
        help='',
        dest='output'
    )
    args = parser.parse_args()
    return args


def load(subredditname: str, to_load_count: int, top_type_to_load: str, after_id: str):
    print("REQUESTING")
    request_url = 'https://www.reddit.com/r/' + \
                  str(subredditname) + '/top/.json?limit=' + str(to_load_count) + '&t=' + \
                  str(top_type_to_load)
    if after_id is not None:
        request_url = 'https://www.reddit.com/r/' + \
                      str(subredditname) + '/top/.json?limit=' + str(to_load_count) + '&t=' + \
                      str(top_type_to_load) + '&after=' + str(after_id)
    r = requests.get(request_url, headers={'User-Agent': 'The Onion Or Not The Onion Drinking Game Bot v1.0'})
    if r.status_code != 200:
        print("ERROR: status_code was \"" + str(r.status_code) + "\"")
        exit(-1)
    return r.json()


def main():
    # Parameter
    args = parse_args()
    type = args.type
    count = args.count
    output = args.output
    max_per_page = 100
    subredditname = args.subredditname
    downloaded_collection = []

    # Web
    current_count = 0
    last_after_id = None
    while current_count < count:
        print("while")
        print("current_count: " + str(current_count))
        print("count: " + str(count))
        print("last_after_id: " + str(last_after_id))
        newly_to_load_count = count - current_count
        if newly_to_load_count > max_per_page:
            newly_to_load_count = max_per_page
        newly_loaded = load(subredditname, newly_to_load_count, type, last_after_id)
        if newly_loaded is not None:
            current_count = current_count + len(newly_loaded["data"]["children"])
            downloaded_collection.extend(newly_loaded["data"]["children"])
            last_after_id = newly_loaded["data"]["after"]

    # Transform
    print("Transforming \"" + str(len(downloaded_collection)) + "\" items...")
    downloaded_improved_collection = []
    for item in downloaded_collection:
        new_item = {}
        # url
        if "url" in item["data"].keys():
            new_item["url"] = item["data"]["url"]
        # selftext
        if "selftext" in item["data"].keys():
            new_item["selftext"] = item["data"]["selftext"]
        # permalink
        if "permalink" in item["data"].keys():
            new_item["permalink"] = item["data"]["permalink"]
        # subreddit
        if "subreddit" in item["data"].keys():
            new_item["subreddit"] = item["data"]["subreddit"]
        # subreddit
        if "subreddit_id" in item["data"].keys():
            new_item["subreddit_id"] = item["data"]["subreddit_id"]
        # downs
        if "downs" in item["data"].keys():
            new_item["downs"] = item["data"]["downs"]
        # subreddit
        if "ups" in item["data"].keys():
            new_item["ups"] = item["data"]["ups"]
        # over_18
        if "over_18" in item["data"].keys():
            new_item["over_18"] = item["data"]["over_18"]
        # title
        if "title" in item["data"].keys():
            new_item["title"] = item["data"]["title"]
        # id
        if "id" in item["data"].keys():
            new_item["id"] = item["data"]["id"]
        # score
        if "score" in item["data"].keys():
            new_item["score"] = item["data"]["score"]
        # thumbnail
        if "thumbnail" in item["data"].keys():
            new_item["thumbnail"] = item["data"]["thumbnail"]
        # thumbnail_width
        if "thumbnail_width" in item["data"].keys():
            new_item["thumbnail_width"] = item["data"]["thumbnail_width"]
        # preview.images[0].source.url
        if "preview" in item["data"].keys():
            if "images" in item["data"]["preview"].keys():
                if "source" in item["data"]["preview"]["images"][0].keys():
                    if "url" in item["data"]["preview"]["images"][0]["source"].keys():
                        new_item["image_url"] = item["data"]["preview"]["images"][0]["source"]["url"]
                    if "width" in item["data"]["preview"]["images"][0]["source"].keys():
                        new_item["image_width"] = item["data"]["preview"]["images"][0]["source"]["width"]
                    if "height" in item["data"]["preview"]["images"][0]["source"].keys():
                        new_item["image_height"] = item["data"]["preview"]["images"][0]["source"]["height"]

        downloaded_improved_collection.append(new_item)

    # Result
    f = open(output, "w")
    f.write(json.dumps(downloaded_improved_collection))
    print("::::" + str(len(downloaded_improved_collection)) + "::::")
    return


if __name__ == "__main__":
    main()
