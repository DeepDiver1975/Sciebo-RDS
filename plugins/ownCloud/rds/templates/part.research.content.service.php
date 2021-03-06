<h2><?php p($l->t('Project')); ?> {{research.researchIndex}}</h2>

<p class="section-text"><h1><?php p($l->t('Here you can enter all informations about your research synchronization.')); ?></p>

<div id="wrapper-services">
  {{#each services}}
  <hr />
  <div id="selector-available-services">
    <h3>{{ servicename }}:</h3>
    {{#unless type.metadata}}
      {{#if type.fileStorage}}
      <p class="section-service">
      <?php p($l->t('Please select a folder where you want to take your files from.')); ?>
      </p>
      <div id="fileStorage-wrapper">
        <label><button id="btn-open-folderpicker" data-service="{{servicename}}"><?php p($l->t('Select folder')); ?></button>
          {{#if filepath }}
            <?php p($l->t('Current path:')); ?>
            <span id="fileStorage-path-{{servicename}}">
                {{filepath}}
            </span>
          {{else}}
            <?php p($l->t('No path currently selected.')); ?>
          {{/if}}
        </label>
      </div>
      {{/if}}
    {{else}}
    <p class="section-service">
      <?php p($l->t('Please select a project where you want to take your files to.')); ?>
    </p>
    <div id="radiobuttons-list">
      {{#each serviceProjects}}
      <label>
          <input
            type="radio"
            name="radiobutton-{{ ../servicename }}"
            id="{{ ../servicename }}-{{metadata.prereserve_doi.recid}}"
            value="{{metadata.prereserve_doi.recid}}"
            {{ checked }}
          />
        {{#if metadata.title}}
          {{ metadata.title }}
        {{else}}
          <?php p($l->t('Project DOI')); ?>: {{metadata.prereserve_doi.doi}} (<?php p($l->t('No title found.')); ?>)
        {{/if}}
      </label>
      {{else}}
      <?php p($l->t('No projects found.')); ?>
      {{/each}}
      <label>
          <input
            type="radio"
            name="radiobutton-{{ servicename }}"
            class="radiobutton-new-project"
            id="new-project-{{ servicename }}"
            data-servicename="{{ servicename }}"
          />
          <?php p($l->t('Create new project.')); ?>
      </label>
    </div>
    {{/unless}}
    <div id="service-configuration">
      <div id="service-configuration-transfergoing">
        <?php p($l->t('For which transfer, do you want to use this service?')); ?> 
        <a target="_blank" rel="noreferrer" class="icon-info"  href="#" title="<?php p($l->t('Informations about ingoing and outgoing traffic.')); ?>"></a>
        <label>
          <input
            type="checkbox"
            name="checkbox-{{ servicename }}-going"
            id="checkbox-{{ servicename }}-ingoing"
            value="portIn"
            {{ importChecked }}
          />
          <?php p($l->t('Ingoing')); ?>
        </label>
        <label>
          <input
            type="checkbox"
            name="checkbox-{{ servicename }}-going"
            id="checkbox-{{ servicename }}-outgoing"
            value="portOut"
            {{ exportChecked }}
          />
          <?php p($l->t('Outgoing')); ?>
        </label>
      </div>
      <div id="service-configuration-status">
      <?php p($l->t('For which storage, do you want to use this service?')); ?>
      <a target="_blank" rel="noreferrer" class="icon-info"  href="#" title="<?php p($l->t('Informations about storage for metadata and fileStorage.')); ?>"></a>
      {{#if type.fileStorage}}
        <label>
          <input
            type="checkbox"
            name="checkbox-{{ servicename }}-property"
            id="checkbox-{{ servicename }}-filestorage"
            value="fileStorage"
            {{ fileStorageChecked }}
          />
          <?php p($l->t('File Storage')); ?>
        </label>
        {{/if}}
        {{#if type.metadata}}
        <label>
          <input
            type="checkbox"
            name="checkbox-{{ servicename }}-property"
            id="checkbox-{{ servicename }}-metadata"
            value="metadata"
            {{ metadataChecked }}
          />
          <?php p($l->t('Metadata Storage')); ?>
        </label>
        {{/if}}
      </div>
    </div>
  </div>
  {{/each}}
</div>

<div id="wrapper-custom-buttons">
  <div id="spacer"></div>
  <button id="btn-save-research"><?php p($l->t('Save')); ?></button>
  <button id="btn-save-research-and-continue"><?php p($l->t('Save & continue')); ?></button>
  <button id="btn-sync-files-in-research"><?php p($l->t('Sync files')); ?></button>
</div>
