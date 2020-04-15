import logging
import os
from lib.upload_zenodo import Zenodo
from lib.Util import require_api_key
from flask import jsonify, request, g

logger = logging.getLogger()


# FIXME: all endpoints need server tests, but POST cannot currently be tested through pactman, because it only supports json as content type
@require_api_key
def index(project_id):
    return g.zenodo.get_files_from_deposition(project_id)


@require_api_key
def get(project_id, file_id):
    return g.zenodo.get_files_from_deposition(project_id)[file_id]


@require_api_key
def post(project_id):
    logger.debug("Read file from request")
    file = request.files['file']

    logger.debug("Start file upload")
    from urllib.parse import unquote
    resp = g.zenodo.upload_new_file_to_deposition(
        project_id, unquote(file.filename), file)
    logger.debug("Finished file upload")

    if resp:
        return jsonify({"success": True})
    else:
        raise ValueError("Upload failed.")


@require_api_key
def patch(project_id, file_id):
    raise NotImplementedError()


@require_api_key
def delete(project_id, file_id):
    raise NotImplementedError()
