var Song = Backbone.Model.extend({});

var Playlist = Backbone.Collection.extend({
  model: Song,
  url: '/playlist',
  comparator: 'order'
});

var audio;

audiojs.events.ready(function() {
  var as = audiojs.createAll({
    trackEnded: function() {
      console.log('Track ended');
    // },
    // loadError: function() {
    //   console.log('Load error');
    }
  });
  audio = as[0];

  var playlist = new Playlist();
  playlist.fetch();

  playlist.on('reset', function() {
    console.log('Playlist reset', playlist.toJSON());

    var song = playlist.first();
    var url = song.get('song_media_url');
    console.log('Loading', url);
    audio.load(url);

    playlist.each(function(song) {
      var item = $('<li>');
      item.text(song.get('song_name'));
      item.attr('data-src', song.get('song_media_url'));
      item.appendTo('#playlist');
    });

    $('#playlist li').click(function(e) {
      e.preventDefault();
      $(this).addClass('playing').siblings().removeClass('playing');
      audio.load($(this).attr('data-src'));
      audio.play();
    });
  });
});